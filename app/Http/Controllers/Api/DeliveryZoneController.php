<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    /**
     * List active delivery zones. Public — used by the app checkout flow
     * to render the governorate picker and preview the delivery fee.
     */
    public function index()
    {
        $zones = DeliveryZone::active()->get()->map(fn ($z) => [
            'id'                       => $z->id,
            'name'                     => $z->name,
            'name_ar'                  => $z->name_ar,
            'code'                     => $z->code,
            'delivery_fee'             => (float) $z->delivery_fee,
            'free_shipping_threshold'  => $z->free_shipping_threshold !== null
                ? (float) $z->free_shipping_threshold
                : null,
            'estimated_days_min'       => $z->estimated_days_min,
            'estimated_days_max'       => $z->estimated_days_max,
        ]);

        return response()->json([
            'success' => true,
            'zones'   => $zones,
        ]);
    }

    /**
     * Show a single zone by id — useful for the checkout confirmation step.
     */
    public function show(DeliveryZone $deliveryZone)
    {
        if (!$deliveryZone->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Zone de livraison indisponible.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'zone'    => [
                'id'                       => $deliveryZone->id,
                'name'                     => $deliveryZone->name,
                'name_ar'                  => $deliveryZone->name_ar,
                'code'                     => $deliveryZone->code,
                'delivery_fee'             => (float) $deliveryZone->delivery_fee,
                'free_shipping_threshold'  => $deliveryZone->free_shipping_threshold !== null
                    ? (float) $deliveryZone->free_shipping_threshold
                    : null,
                'estimated_days_min'       => $deliveryZone->estimated_days_min,
                'estimated_days_max'       => $deliveryZone->estimated_days_max,
            ],
        ]);
    }
}
