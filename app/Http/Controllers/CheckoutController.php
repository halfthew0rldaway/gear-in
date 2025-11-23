<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private array $paymentOptions = [
        'bank_transfer' => 'Bank Transfer (Virtual Account)',
        'cod' => 'Cash on Delivery',
        'ewallet' => 'E-Wallet (OVO/Dana/GoPay)',
    ];

    private array $shippingOptions = [
        'standard' => [
            'label' => 'Standard Courier (2-3 hari)',
            'fee' => 15000,
        ],
        'express' => [
            'label' => 'Express Courier (1 hari)',
            'fee' => 35000,
        ],
        'same_day' => [
            'label' => 'Same-Day Courier',
            'fee' => 45000,
        ],
    ];

    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->totals($request->user());

        if ($cart['items']->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Keranjang Anda masih kosong.',
            ]);
        }

        return view('storefront.checkout', [
            'cartItems' => $cart['items'],
            'subtotal' => $cart['subtotal'],
            'shipping' => $cart['shipping'],
            'total' => $cart['total'],
            'user' => $request->user(),
            'paymentOptions' => $this->paymentOptions,
            'shippingOptions' => $this->shippingOptions,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $cartItems = $this->cartService->getItems($user);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Keranjang Anda masih kosong.',
            ]);
        }

        $data = $request->validated();

        $shippingOption = $this->shippingOptions[$data['shipping_method']] ?? null;

        if (! $shippingOption) {
            throw ValidationException::withMessages([
                'shipping_method' => 'Metode pengiriman tidak valid.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $user, $cartItems, $shippingOption) {
            $lineItems = [];
            $subtotal = 0;

            foreach ($cartItems as $item) {
                $product = $item->product()->lockForUpdate()->first();

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'Produk tidak tersedia.',
                    ]);
                }

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi.",
                    ]);
                }

                $lineTotal = $product->price * $item->quantity;
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product' => $product,
                    'payload' => [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_image' => $product->image_path,
                        'unit_price' => $product->price,
                        'quantity' => $item->quantity,
                        'line_total' => $lineTotal,
                    ],
                ];
            }

            $shippingFee = $shippingOption['fee'];
            $total = $subtotal + $shippingFee;

            $order = $user->orders()->create([
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'shipping_method' => $data['shipping_method'],
                'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'waiting',
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'address_line1' => $data['address_line1'],
                'address_line2' => $data['address_line2'] ?? null,
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'notes' => $data['notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($lineItems as $lineItem) {
                $order->items()->create($lineItem['payload']);
                $lineItem['product']->decrement('stock', $lineItem['payload']['quantity']);
            }

            return $order;
        });

        $this->cartService->clear($user);

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Pesanan berhasil dibuat. Kami akan segera memprosesnya.');
    }
}
