<?php

namespace Tests\Feature;

use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderAdminNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_order_confirmation_sent_to_authenticated_user(): void
    {
        $user = User::factory()->create(['email' => 'customer@example.com']);
        
        $product = Product::factory()->create(['is_active' => true]);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->post(route('checkout.process'), [
                'customer_name' => 'Test Customer',
                'customer_phone' => '+375291234567',
                'customer_email' => 'customer@example.com',
                'delivery_method' => $deliveryMethod->id,
                'payment_method' => $paymentMethod->id,
                'city' => 'Минск',
                'street' => 'ул. Тестовая',
                'house' => '1',
                'apartment' => '1',
            ]);

        Notification::assertSentTo(
            $user,
            OrderConfirmationNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }

    public function test_order_confirmation_sent_to_guest_email(): void
    {
        $product = Product::factory()->create(['is_active' => true]);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->post(route('checkout.process'), [
            'customer_name' => 'Guest Customer',
            'customer_phone' => '+375291234567',
            'customer_email' => 'guest@example.com',
            'delivery_method' => $deliveryMethod->id,
            'payment_method' => $paymentMethod->id,
            'city' => 'Минск',
            'street' => 'ул. Тестовая',
            'house' => '1',
            'apartment' => '1',
        ]);

        Notification::assertSentOnDemand(
            OrderConfirmationNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'guest@example.com';
            }
        );
    }

    public function test_admin_notification_sent(): void
    {
        config(['mail.admin_email' => 'admin@example.com']);

        $product = Product::factory()->create(['is_active' => true]);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->post(route('checkout.process'), [
            'customer_name' => 'Test Customer',
            'customer_phone' => '+375291234567',
            'customer_email' => 'customer@example.com',
            'delivery_method' => $deliveryMethod->id,
            'payment_method' => $paymentMethod->id,
            'city' => 'Минск',
            'street' => 'ул. Тестовая',
            'house' => '1',
            'apartment' => '1',
        ]);

        Notification::assertSentOnDemand(
            NewOrderAdminNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'admin@example.com';
            }
        );
    }

    public function test_admin_notification_not_sent_when_email_not_configured(): void
    {
        config(['mail.admin_email' => null]);

        $product = Product::factory()->create(['is_active' => true]);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->post(route('checkout.process'), [
            'customer_name' => 'Test Customer',
            'customer_phone' => '+375291234567',
            'customer_email' => 'customer@example.com',
            'delivery_method' => $deliveryMethod->id,
            'payment_method' => $paymentMethod->id,
            'city' => 'Минск',
            'street' => 'ул. Тестовая',
            'house' => '1',
            'apartment' => '1',
        ]);

        Notification::assertNothingSentTo(
            Notification::route('mail', 'admin@example.com'),
            NewOrderAdminNotification::class
        );
    }

    public function test_order_confirmation_contains_order_details(): void
    {
        $user = User::factory()->create(['email' => 'customer@example.com']);
        
        $product = Product::factory()->create(['is_active' => true, 'name' => 'Test Product']);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->post(route('checkout.process'), [
                'customer_name' => 'Test Customer',
                'customer_phone' => '+375291234567',
                'customer_email' => 'customer@example.com',
                'delivery_method' => $deliveryMethod->id,
                'payment_method' => $paymentMethod->id,
                'city' => 'Минск',
                'street' => 'ул. Тестовая',
                'house' => '1',
                'apartment' => '1',
            ]);

        Notification::assertSentTo(
            $user,
            OrderConfirmationNotification::class,
            function ($notification) {
                $mailMessage = $notification->toMail($notification->notifiable);
                
                return str_contains($mailMessage->subject, 'Заказ #')
                    && str_contains($mailMessage->greeting, 'Здравствуйте');
            }
        );
    }

    public function test_admin_notification_contains_customer_info(): void
    {
        config(['mail.admin_email' => 'admin@example.com']);

        $product = Product::factory()->create(['is_active' => true]);
        $deliveryMethod = DeliveryMethod::factory()->create(['is_active' => true]);
        $paymentMethod = PaymentMethod::factory()->create(['is_active' => true]);

        $this->post(route('checkout.process'), [
            'customer_name' => 'John Doe',
            'customer_phone' => '+375291234567',
            'customer_email' => 'john@example.com',
            'delivery_method' => $deliveryMethod->id,
            'payment_method' => $paymentMethod->id,
            'city' => 'Минск',
            'street' => 'ул. Тестовая',
            'house' => '1',
            'apartment' => '1',
        ]);

        Notification::assertSentOnDemand(
            NewOrderAdminNotification::class,
            function ($notification) {
                $mailMessage = $notification->toMail($notification->notifiable);
                
                return str_contains($mailMessage->subject, 'Новый заказ #');
            }
        );
    }
}
