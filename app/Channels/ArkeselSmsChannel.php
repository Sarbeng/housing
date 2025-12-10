<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ArkeselSmsChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification): void
    {
        // 1. Get the recipient's phone number
        /// 1. Get the recipient's phone number
        $to = $notifiable->routeNotificationFor('arkesel', $notification);

        // *** NEW: Check for and use the test number if it's set ***
        $testPhone = config('services.arkesel.test_phone');

        if (!empty($testPhone)) {
            // If a test phone is set in config, use it instead of the recipient's number
            $to = $testPhone;
            \Log::info("Using ARKESEL_TEST_PHONE: {$to} for notification intended for {$notifiable->id}");
        }
        // *** END NEW ***

        if (! $to) {
            return;
        }

        // 2. Get the message content from the Notification
        $message = $notification->toArkesel($notifiable);

        // Ensure the message is valid
        if (empty($message)) {
            return;
        }

        // 3. Define the Arkesel API endpoint and parameters
        $url = 'https://sms.arkesel.com/sms/api';
        $params = [
            'action' => 'send-sms',
            'api_key' => config('services.arkesel.api_key'),
            'to' => $to,
            'from' => config('services.arkesel.sender_id'),
            'sms' => $message,
        ];

        // 4. Send the request using Laravel's HTTP Client
        try {
            $response = Http::timeout(10)->get($url, $params);

            // You can log the response for debugging
            // \Log::info('Arkesel SMS Response: ' . $response->body());

        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection errors)
            \Log::error('Arkesel SMS Failed: ' . $e->getMessage());
        }
    }
}
