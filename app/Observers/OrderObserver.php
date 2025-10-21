<?php
namespace App\Observers;

use App\Models\Orders;
use App\Http\Controllers\Api\PushNotificationController;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     */
    public function updating(Orders $order)
    {
        // Check if the 'status' field is being updated
        if (false){//$order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
        
            // Convert status values to Arabic names
            $oldStatusArabic = $order->getStatusAttribute($oldStatus);
            $newStatusArabic = $order->getStatusAttribute($newStatus);
        
            // Get user's FCM token
            $fcmToken = $order->client->fcm_token ?? null;
        
            if (!empty($fcmToken)) {
                $pushNotificationController = new PushNotificationController();
                $pushNotificationController->sendBulkPushNotification([
                    'title' => "تحديث حالة الطلب",
                    'description' => "تم تغيير حالة طلبك من '$oldStatusArabic' إلى '$newStatusArabic'.",
                    'token' => $fcmToken,
                ]);
            }
        }

    }
}
