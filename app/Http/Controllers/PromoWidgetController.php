<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoWidgetController extends Controller
{
    public function close(Request $request): JsonResponse
    {
        session(['promo_widget_closed' => true]);
        session()->forget('promo_widget_minimized');

        return response()->json(['success' => true]);
    }

    public function minimize(Request $request): JsonResponse
    {
        $minimize = $request->input('minimize', true);
        
        if ($minimize) {
            session(['promo_widget_minimized' => true]);
        } else {
            session()->forget('promo_widget_minimized');
        }

        return response()->json(['success' => true]);
    }

    public function reset(Request $request): JsonResponse
    {
        session()->forget(['promo_widget_closed', 'promo_widget_minimized']);

        return response()->json(['success' => true, 'message' => 'Promo widget state reset']);
    }
}
