<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReceiptProcessingService
{
    /**
     * Process uploaded receipt and extract reference ID
     */
    public function processReceipt(UploadedFile $file, string $orderId): array
    {
        try {
            // Store the file
            $filePath = $file->store('receipts', 'public');
            
            // Extract reference ID from the receipt
            $referenceId = $this->extractReferenceId($file, $orderId);
            
            return [
                'success' => true,
                'file_path' => $filePath,
                'reference_id' => $referenceId,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Receipt processing failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'file_name' => $file->getClientOriginalName(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Extract reference ID from receipt (image or PDF)
     */
    private function extractReferenceId(UploadedFile $file, string $orderId): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return $this->extractFromImage($file, $orderId);
        } elseif ($extension === 'pdf') {
            return $this->extractFromPdf($file, $orderId);
        }
        
        return null;
    }
    
    /**
     * Extract reference ID from image using OCR
     */
    private function extractFromImage(UploadedFile $file, string $orderId): ?string
    {
        try {
            // Get the image content
            $imageContent = file_get_contents($file->getPathname());
            
            // Use Google Cloud Vision API for OCR
            $extractedText = $this->performOCR($imageContent);
            
            if (!$extractedText) {
                Log::warning('No text extracted from image', [
                    'order_id' => $orderId,
                    'file_name' => $file->getClientOriginalName(),
                ]);
                return null;
            }
            
            // Extract reference ID using patterns
            $referenceId = $this->extractReferenceFromText($extractedText);
            
            Log::info('OCR processing completed', [
                'order_id' => $orderId,
                'file_name' => $file->getClientOriginalName(),
                'extracted_text_length' => strlen($extractedText),
                'reference_found' => $referenceId !== null,
                'reference' => $referenceId,
            ]);
            
            return $referenceId;
            
        } catch (\Exception $e) {
            Log::error('OCR processing failed', [
                'order_id' => $orderId,
                'file_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }
    
    /**
     * Perform OCR on image content
     */
    private function performOCR(string $imageContent): ?string
    {
        // Option 1: Google Cloud Vision API
        if (config('services.google_vision.enabled', false)) {
            return $this->googleVisionOCR($imageContent);
        }
        
        // Option 2: Tesseract OCR (primary method)
        if (class_exists('thiagoalessio\TesseractOCR\TesseractOCR')) {
            return $this->tesseractOCR($imageContent);
        }
        
        // Fallback: No OCR available
        Log::warning('No OCR service available. Tesseract OCR not installed.');
        return null;
    }
    
    /**
     * Google Cloud Vision API OCR
     */
    private function googleVisionOCR(string $imageContent): ?string
    {
        try {
            $client = new \Google\Cloud\Vision\V1\ImageAnnotatorClient([
                'credentials' => config('services.google_vision.credentials_path'),
            ]);
            
            $image = (new \Google\Cloud\Vision\V1\Image())
                ->setContent($imageContent);
            
            $response = $client->textDetection($image);
            $texts = $response->getTextAnnotations();
            
            if (count($texts) > 0) {
                return $texts[0]->getDescription();
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Google Vision OCR failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Tesseract OCR
     */
    private function tesseractOCR(string $imageContent): ?string
    {
        try {
            // Save temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'ocr_');
            file_put_contents($tempFile, $imageContent);
            
            $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($tempFile);
            
            // Configure Tesseract for better receipt text recognition
            $ocr->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe')
                ->psm(6)              // Assume a single uniform block of text
                ->oem(3)              // Use LSTM OCR Engine Mode
                ->lang('eng')         // English language
                ->allowlist(range('0', '9'), range('A', 'Z'), range('a', 'z'), ':', '-', ' '); // Only allow alphanumeric and common receipt characters
            
            $text = $ocr->run();
            
            // Clean up
            unlink($tempFile);
            
            Log::info('Tesseract OCR completed', [
                'extracted_text_length' => strlen($text),
                'text_preview' => substr($text, 0, 200) . (strlen($text) > 200 ? '...' : '')
            ]);
            
            return $text;
            
        } catch (\Exception $e) {
            Log::error('Tesseract OCR failed', [
                'error' => $e->getMessage(),
                'file_size' => strlen($imageContent)
            ]);
            return null;
        }
    }
    
    
    /**
     * Extract reference ID from OCR text using patterns
     */
    private function extractReferenceFromText(string $text): ?string
    {
        // Clean the text first
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        Log::info('Extracting reference from text', [
            'text_length' => strlen($text),
            'text_sample' => substr($text, 0, 300)
        ]);
        
        $patterns = $this->getReferencePatterns();
        
        // Try bank-specific patterns first
        foreach ($patterns as $bank => $pattern) {
            if (preg_match($pattern['pattern'], $text, $matches)) {
                if (isset($matches[1])) {
                    $reference = trim($matches[1]);
                    Log::info('Reference found with bank pattern', [
                        'bank' => $bank,
                        'pattern' => $pattern['pattern'],
                        'reference' => $reference
                    ]);
                    return $reference;
                }
            }
        }
        
        // Try to find alphanumeric references (most common format - digits + letters)
        if (preg_match('/([A-Z0-9]{8,})/', $text, $matches)) {
            $reference = $matches[1];
            Log::info('Reference found with alphanumeric pattern', [
                'reference' => $reference
            ]);
            return $reference;
        }
        
        // Try to find any sequence of 8+ digits (fallback for numeric-only references)
        if (preg_match('/(\d{8,})/', $text, $matches)) {
            $reference = $matches[1];
            Log::info('Reference found with digit pattern', [
                'reference' => $reference
            ]);
            return $reference;
        }
        
        Log::warning('No reference pattern matched', [
            'text' => $text
        ]);
        
        return null;
    }
    
    /**
     * Extract reference ID from PDF
     */
    private function extractFromPdf(UploadedFile $file, string $orderId): ?string
    {
        // For now, return a placeholder
        // In production, you would integrate with PDF text extraction services
        
        Log::info('PDF text extraction', [
            'order_id' => $orderId,
            'file_name' => $file->getClientOriginalName(),
        ]);
        
        // Placeholder implementation
        // You would implement actual PDF text extraction here
        return 'PDF-' . time() . '-' . substr($orderId, -4);
    }
    
    /**
     * Validate receipt format and content
     */
    public function validateReceipt(UploadedFile $file): array
    {
        $errors = [];
        
        // Check file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            $errors[] = 'File size must be less than 5MB';
        }
        
        // Check file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = 'File must be a JPG, PNG, or PDF';
        }
        
        // Check if file is actually an image/PDF (basic validation)
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                $errors[] = 'Invalid image file';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Get suggested reference ID patterns for Malaysian banks
     */
    public function getReferencePatterns(): array
    {
        return [
            'Maybank' => [
                'pattern' => '/TXN\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Maybank transaction reference (TXN: followed by 8+ alphanumeric)',
            ],
            'CIMB' => [
                'pattern' => '/Ref\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'CIMB reference number (Ref: followed by 8+ alphanumeric)',
            ],
            'Public Bank' => [
                'pattern' => '/Reference\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Public Bank reference (Reference: followed by 8+ alphanumeric)',
            ],
            'RHB' => [
                'pattern' => '/Ref\s*No\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'RHB reference number (Ref No: followed by 8+ alphanumeric)',
            ],
            'Hong Leong' => [
                'pattern' => '/Txn\s*Ref\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Hong Leong transaction reference (Txn Ref: followed by 8+ alphanumeric)',
            ],
            'Bank Islam' => [
                'pattern' => '/Transaction\s*ID\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Bank Islam transaction ID (Transaction ID: followed by 8+ alphanumeric)',
            ],
            'AmBank' => [
                'pattern' => '/Payment\s*Ref\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'AmBank payment reference (Payment Ref: followed by 8+ alphanumeric)',
            ],
            'Alliance Bank' => [
                'pattern' => '/Ref\s*Number\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Alliance Bank reference number (Ref Number: followed by 8+ alphanumeric)',
            ],
            'Generic' => [
                'pattern' => '/(?:ref|reference|txn|transaction|payment\s*ref|ref\s*no|ref\s*number)\s*:?\s*([A-Z0-9]{8,})/i',
                'description' => 'Generic reference pattern (8+ alphanumeric)',
            ],
        ];
    }
}
