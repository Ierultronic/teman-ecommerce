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
        }
    }

    public function getCartQuantity($productId, $variantId = null)
    {
        $cartKey = $productId . '_' . ($variantId ?? 'base');
        return isset($this->cart[$cartKey]) ? $this->cart[$cartKey]['quantity'] : 0;
    }

    public function getCartKey($productId, $variantId = null)
    {
        return $productId . '_' . ($variantId ?? 'base');
    }

    public function getProductTotalStock($product)
    {
        $baseStock = 0; // Base products don't have stock, only variants do
        $variantStock = $product->variants->sum('stock');
        
        return $baseStock + $variantStock;
    }

    public function isProductInStock($product, $variantId = null)
    {
        if ($variantId) {
            $variant = $product->variants->find($variantId);
            return $variant && $variant->stock > 0;
        }
        
        return $this->getProductTotalStock($product) > 0;
    }

    public function addToCart($productId, $variantId = null, $quantity = 1)
    {
        $product = Product::find($productId);
        $variant = $variantId ? $product->variants()->find($variantId) : null;
        
        $cartKey = $productId . '_' . ($variantId ?? 'base');
        
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
                'variant_name' => $variant ? $variant->variant_name : 'Base',
                'price' => $product->price,
            ];
        }

        $this->dispatch('cart-updated');
    }

    public function updateCart($productId, $variantId = null, $quantity = 1)
    {
        $cartKey = $productId . '_' . ($variantId ?? 'base');
        
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
        $availableStock = $this->getAvailableStock($productId, $variantId);
        
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
        $this->updateQuantity($productId, $currentQty + 1);
    }

    public function decrementQuantity($productId)
    {
        $currentQty = $this->quantity[$productId] ?? 1;
        if ($currentQty > 1) {
            $this->updateQuantity($productId, $currentQty - 1);
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

    public function placeOrder()
    {
        $this->validate([
            'customerName' => 'required|string|max:150',
            'customerEmail' => 'required|email|max:150',
            'customerPhone' => 'nullable|string|max:30',
        ]);

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
            $this->reset(['customerName', 'customerEmail', 'customerPhone']);
            
            $this->dispatch('order-placed', [
                'message' => 'Order placed successfully! Order ID: ' . $order->id
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
        
        return $this->getProductTotalStock($product);
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
        
        return $this->getProductTotalStock($product);
    }

    public function updatedSelectedVariant($value, $key)
    {
        // Safely extract product ID from the key
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $productId = $parts[1];
            // Reset quantity to 1 when variant changes
            $this->quantity[$productId] = 1;
        }
        
        // Refresh the component to update stock display
        $this->dispatch('$refresh');
    }

    public function updatedQuantity($value, $key)
    {
        // Extract product ID from the key (e.g., "quantity.1" -> "1")
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $productId = $parts[1];
            $quantity = (int) $value;
            
            // Validate and update the quantity
            $this->updateQuantity($productId, $quantity);
        }
    }

    public function render()
    {
        return view('livewire.store-page');
    }
}
