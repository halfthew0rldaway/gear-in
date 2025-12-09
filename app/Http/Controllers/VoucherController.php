<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct(
        private readonly VoucherService $voucherService,
        private readonly CartService $cartService
    ) {
    }

    public function validate(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu.',
                ], 401);
            }
            
            $code = $request->input('code');
            
            if (empty($code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode voucher tidak boleh kosong.',
                ], 422);
            }
            
            // Handle selected_items - bisa berupa JSON string atau array
            $selectedItemsInput = $request->input('selected_items', []);
            $selectedItemIds = [];
            
            if (!empty($selectedItemsInput)) {
                if (is_string($selectedItemsInput)) {
                    $decoded = json_decode($selectedItemsInput, true);
                    $selectedItemIds = is_array($decoded) ? $decoded : [];
                } elseif (is_array($selectedItemsInput)) {
                    $selectedItemIds = $selectedItemsInput;
                }
            }

            // Get cart totals
            if (empty($selectedItemIds)) {
                $cart = $this->cartService->totals($user);
            } else {
                $cart = $this->cartService->totalsForItems($user, $selectedItemIds);
            }

            // Validate voucher
            $result = $this->voucherService->validate($code, $user, $cart['subtotal']);

            if ($result['valid']) {
                // Recalculate with voucher
                if (empty($selectedItemIds)) {
                    $cart = $this->cartService->totals($user, $result['voucher']);
                } else {
                    $cart = $this->cartService->totalsForItems($user, $selectedItemIds, $result['voucher']);
                }

                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'discount' => $cart['discount'],
                    'total' => $cart['total'],
                    'voucher' => [
                        'code' => $result['voucher']->code,
                        'name' => $result['voucher']->name,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Voucher validation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi voucher. Silakan coba lagi.',
            ], 500);
        }
    }
}
