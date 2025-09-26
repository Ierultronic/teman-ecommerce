<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class StorePage extends Component
{
    use WithFileUploads;

    public $products;
    public $cart = [];
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $customerAddressLine1 = '';
    public $customerAddressLine2 = '';
    public $customerCity = '';
    public $customerState = '';
    public $customerPostalCode = '';
    public $customerCountry = '';
    public $shippingName = '';
    public $shippingEmail = '';
    public $shippingPhone = '';
    public $shippingAddressLine1 = '';
    public $shippingAddressLine2 = '';
    public $shippingCity = '';
    public $shippingState = '';
    public $shippingPostalCode = '';
    public $shippingCountry = '';
    public $orderNotes = '';
    public $sameAsBilling = true;
    public $showOrderForm = false;
    public $selectedVariant = [];
    public $quantity = [];

    public function mount()
    {
        $this->products = Product::with(['variants' => function($query) {
            $query->where('stock', '>', 0);
        }])->latest()->get();
        
        // Add stock information to each product
        foreach ($this->products as $product) {
            $product->total_stock = $this->getProductTotalStock($product);
            // Initialize quantity for each product
            $this->quantity[$product->id] = 1;
            // Initialize selectedVariant as null for each product
            $this->selectedVariant[$product->id] = null;
        }
    }

    public function getCartQuantity($productId, $variantId = null)
    {
        if (!$variantId) {
            return 0;
        }
        $cartKey = $productId . '_' . $variantId;
        return isset($this->cart[$cartKey]) ? $this->cart[$cartKey]['quantity'] : 0;
    }

    public function getCartKey($productId, $variantId = null)
    {
        return $productId . '_' . $variantId;
    }

    public function getProductTotalStock($product)
    {
        // Only variants have stock, base products don't
        return $product->variants->sum('stock');
    }

    public function isProductInStock($product, $variantId = null)
    {
        if ($variantId) {
            $variant = $product->variants->find($variantId);
            return $variant && $variant->stock > 0;
        }
        
        // If no variant is selected, return false since we only allow variant selection
        return false;
    }

    public function addToCart($productId, $variantId = null, $quantity = 1)
    {
        // Prevent adding base product (no variant selected)
        if (!$variantId) {
            $this->addError('variant', 'Please select a variant before adding to cart.');
            return;
        }

        $product = Product::find($productId);
        $variant = $variantId ? $product->variants()->find($variantId) : null;
        
        // Additional validation to ensure variant exists and has stock
        if (!$variant || $variant->stock <= 0) {
            $this->addError('variant', 'Selected variant is not available or out of stock.');
            return;
        }
        
        $cartKey = $productId . '_' . $variantId;
        
        if (isset($this->cart[$cartKey])) {
            // If item already exists, update the quantity instead of adding
            $this->cart[$cartKey]['quantity'] = $quantity;
        } else {
            // Add new item to cart
            $this->cart[$cartKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'product_name' => $product->name,
                'variant_name' => $variant->variant_name,
                'price' => $product->price,
            ];
        }

        $this->dispatch('cart-updated');
    }

    public function updateCart($productId, $variantId = null, $quantity = 1)
    {
        // Prevent updating with null variant
        if (!$variantId) {
            $this->addError('variant', 'Please select a variant before updating cart.');
            return;
        }
        
        $cartKey = $productId . '_' . $variantId;
        
        if (isset($this->cart[$cartKey])) {
            // Update existing cart item quantity
            $this->cart[$cartKey]['quantity'] = $quantity;
            $this->dispatch('cart-updated');
        }
    }

    public function removeFromCart($key)
    {
        unset($this->cart[$key]);
        $this->dispatch('cart-updated');
    }

    public function updateQuantity($productId, $quantity)
    {
        $product = $this->products->find($productId);
        if (!$product) return;
        
        // Get selected variant if any
        $variantId = $this->selectedVariant[$productId] ?? null;
        $availableStock = $this->getDisplayStock($productId);
        
        // Ensure quantity is a valid integer
        $quantity = (int) $quantity;
        
        // Validate quantity against available stock
        if ($quantity > 0 && $quantity <= $availableStock) {
            $this->quantity[$productId] = $quantity;
        } elseif ($quantity <= 0) {
            $this->quantity[$productId] = 1;
        } elseif ($quantity > $availableStock) {
            $this->quantity[$productId] = $availableStock;
        }
    }

    public function incrementQuantity($productId)
    {
        $currentQty = $this->quantity[$productId] ?? 1;
        $availableStock = $this->getDisplayStock($productId);
        
        // Only increment if we haven't reached the stock limit
        if ($currentQty < $availableStock) {
            $this->quantity[$productId] = $currentQty + 1;
        }
    }

    public function decrementQuantity($productId)
    {
        $currentQty = $this->quantity[$productId] ?? 1;
        if ($currentQty > 1) {
            $this->quantity[$productId] = $currentQty - 1;
        }
    }

    public function getCartTotal()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getCartCount()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function showOrderForm()
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }
        
        $this->showOrderForm = true;
    }

    public function updatedSameAsBilling($value)
    {
        if ($value) {
            // Copy billing information to shipping fields
            $this->shippingName = $this->customerName;
            $this->shippingEmail = $this->customerEmail;
            $this->shippingPhone = $this->customerPhone;
            $this->shippingAddressLine1 = $this->customerAddressLine1;
            $this->shippingAddressLine2 = $this->customerAddressLine2;
            $this->shippingCity = $this->customerCity;
            $this->shippingState = $this->customerState;
            $this->shippingPostalCode = $this->customerPostalCode;
            $this->shippingCountry = $this->customerCountry;
        } else {
            // Clear shipping fields when unchecked
            $this->shippingName = '';
            $this->shippingEmail = '';
            $this->shippingPhone = '';
            $this->shippingAddressLine1 = '';
            $this->shippingAddressLine2 = '';
            $this->shippingCity = '';
            $this->shippingState = '';
            $this->shippingPostalCode = '';
            $this->shippingCountry = '';
        }
        
        // Force re-render to update the component
        $this->dispatch('$refresh');
    }

    public function placeOrder()
    {
        // Base validation rules
        $rules = [
            'customerName' => 'required|string|max:150',
            'customerEmail' => 'required|email|max:150',
            'customerPhone' => 'nullable|string|max:30',
            'customerAddressLine1' => 'required|string|max:255',
            'customerCity' => 'required|string|max:100',
            'customerState' => 'required|string|max:100',
            'customerPostalCode' => 'required|string|max:20',
            'customerCountry' => 'required|string|max:100',
            'orderNotes' => 'nullable|string|max:1000',
        ];

        // Add shipping validation rules only if shipping is different from billing
        if (!$this->sameAsBilling) {
            $rules = array_merge($rules, [
                'shippingName' => 'required|string|max:150',
                'shippingEmail' => 'required|email|max:150',
                'shippingPhone' => 'nullable|string|max:30',
                'shippingAddressLine1' => 'required|string|max:255',
                'shippingCity' => 'required|string|max:100',
                'shippingState' => 'required|string|max:100',
                'shippingPostalCode' => 'required|string|max:20',
                'shippingCountry' => 'required|string|max:100',
            ]);
        }

        $this->validate($rules);

        if (empty($this->cart)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        try {
            DB::beginTransaction();

            $totalPrice = $this->getCartTotal();

            $order = Order::create([
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'customer_address_line_1' => $this->customerAddressLine1,
                'customer_address_line_2' => $this->customerAddressLine2,
                'customer_city' => $this->customerCity,
                'customer_state' => $this->customerState,
                'customer_postal_code' => $this->customerPostalCode,
                'customer_country' => $this->customerCountry,
                'shipping_name' => $this->shippingName,
                'shipping_email' => $this->shippingEmail,
                'shipping_phone' => $this->shippingPhone,
                'shipping_address_line_1' => $this->shippingAddressLine1,
                'shipping_address_line_2' => $this->shippingAddressLine2,
                'shipping_city' => $this->shippingCity,
                'shipping_state' => $this->shippingState,
                'shipping_postal_code' => $this->shippingPostalCode,
                'shipping_country' => $this->shippingCountry,
                'order_notes' => $this->orderNotes,
                'same_as_billing' => $this->sameAsBilling,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            foreach ($this->cart as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Update stock if variant exists
                if ($item['variant_id']) {
                    $variant = ProductVariant::find($item['variant_id']);
                    $variant->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            // Clear cart and show success
            $this->cart = [];
            $this->showOrderForm = false;
            $this->reset([
                'customerName', 'customerEmail', 'customerPhone',
                'customerAddressLine1', 'customerAddressLine2', 'customerCity',
                'customerState', 'customerPostalCode', 'customerCountry',
                'shippingName', 'shippingEmail', 'shippingPhone',
                'shippingAddressLine1', 'shippingAddressLine2', 'shippingCity',
                'shippingState', 'shippingPostalCode', 'shippingCountry',
                'orderNotes', 'sameAsBilling'
            ]);
            
            $this->dispatch('order-placed', [
                'message' => 'Your order has been placed successfully! We will process it shortly.',
                'orderId' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('order', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function getAvailableStock($productId, $variantId = null)
    {
        $product = $this->products->find($productId);
        if (!$product) return 0;
        
        if ($variantId) {
            $variant = $product->variants->find($variantId);
            return $variant ? $variant->stock : 0;
        }
        
        // If no variant is selected, return 0 since we only allow variant selection
        return 0;
    }

    public function getDisplayStock($productId)
    {
        $product = $this->products->find($productId);
        if (!$product) return 0;
        
        $variantId = $this->selectedVariant[$productId] ?? null;
        
        if ($variantId) {
            $variant = $product->variants->find($variantId);
            return $variant ? $variant->stock : 0;
        }
        
        // If no variant is selected, return 0 since we only allow variant selection
        return 0;
    }

    public function selectVariant($productId, $variantId)
    {
        // Set the selected variant for the product
        $this->selectedVariant[$productId] = $variantId;
        
        // Reset quantity to 1 when variant changes
        $this->quantity[$productId] = 1;
        
        // Validate that the quantity doesn't exceed available stock
        $availableStock = $this->getDisplayStock($productId);
        if ($availableStock <= 0) {
            $this->quantity[$productId] = 0;
        } elseif ($this->quantity[$productId] > $availableStock) {
            $this->quantity[$productId] = $availableStock;
        }
    }

    public function updatedSelectedVariant($value, $key)
    {
        // Safely extract product ID from the key
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $productId = $parts[1];
            
            // Ensure the selectedVariant is properly set
            $this->selectedVariant[$productId] = $value;
            
            // Reset and validate quantity when variant changes
            $availableStock = $this->getDisplayStock($productId);
            if ($availableStock <= 0) {
                $this->quantity[$productId] = 0;
            } else {
                $this->quantity[$productId] = 1;
            }
        }
        
        // No need for $refresh, Livewire will handle the update automatically
    }

    public function updatedQuantity($value, $key)
    {
        // Extract product ID from the key (e.g., "quantity.1" -> "1")
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $productId = $parts[1];
            $quantity = (int) $value;
            $availableStock = $this->getDisplayStock($productId);
            
            // Validate and update the quantity
            if ($quantity <= 0) {
                $this->quantity[$productId] = 1;
            } elseif ($quantity > $availableStock) {
                $this->quantity[$productId] = $availableStock;
            } else {
                $this->quantity[$productId] = $quantity;
            }
        }
    }

    public function validateQuantitiesAgainstStock()
    {
        foreach ($this->quantity as $productId => $qty) {
            $availableStock = $this->getDisplayStock($productId);
            if ($qty > $availableStock && $availableStock > 0) {
                $this->quantity[$productId] = $availableStock;
            } elseif ($availableStock <= 0) {
                $this->quantity[$productId] = 0;
            }
        }
    }

    public function render()
    {
        // Validate quantities against current stock before rendering
        $this->validateQuantitiesAgainstStock();
        
        return view('livewire.store-page');
    }
}
