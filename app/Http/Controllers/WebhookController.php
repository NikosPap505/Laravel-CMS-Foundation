<?php

namespace App\Http\Controllers;

use App\Services\Integration\IntegrationManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected IntegrationManager $integrationManager;

    public function __construct(IntegrationManager $integrationManager)
    {
        $this->integrationManager = $integrationManager;
    }

    /**
     * Handle Shopify webhooks
     */
    public function shopify(Request $request): JsonResponse
    {
        try {
            // Verify webhook signature
            if (!$this->verifyShopifyWebhook($request)) {
                Log::warning('Invalid Shopify webhook signature');
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $data = $request->all();
            $topic = $request->header('X-Shopify-Topic');

            Log::info('Shopify webhook received', [
                'topic' => $topic,
                'shop' => $request->header('X-Shopify-Shop-Domain')
            ]);

            // Process based on webhook topic
            switch ($topic) {
                case 'orders/updated':
                    $this->processShopifyOrderUpdate($data);
                    break;
                case 'orders/created':
                    $this->processShopifyOrderCreate($data);
                    break;
                case 'products/update':
                    $this->processShopifyProductUpdate($data);
                    break;
                default:
                    Log::info('Unhandled Shopify webhook topic', ['topic' => $topic]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Shopify webhook processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle Mailchimp webhooks
     */
    public function mailchimp(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $type = $data['type'] ?? 'unknown';

            Log::info('Mailchimp webhook received', ['type' => $type]);

            switch ($type) {
                case 'subscribe':
                    $this->processMailchimpSubscribe($data);
                    break;
                case 'unsubscribe':
                    $this->processMailchimpUnsubscribe($data);
                    break;
                case 'profile':
                    $this->processMailchimpProfileUpdate($data);
                    break;
                default:
                    Log::info('Unhandled Mailchimp webhook type', ['type' => $type]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Mailchimp webhook processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle Stripe webhooks
     */
    public function stripe(Request $request): JsonResponse
    {
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $endpointSecret = config('integrations.stripe.webhook_secret');

            // Verify webhook signature
            if (!$this->verifyStripeWebhook($payload, $sigHeader, $endpointSecret)) {
                Log::warning('Invalid Stripe webhook signature');
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $event = json_decode($payload, true);
            $eventType = $event['type'];

            Log::info('Stripe webhook received', ['type' => $eventType]);

            switch ($eventType) {
                case 'charge.succeeded':
                    $this->processStripeChargeSucceeded($event['data']['object']);
                    break;
                case 'charge.failed':
                    $this->processStripeChargeFailed($event['data']['object']);
                    break;
                case 'customer.created':
                    $this->processStripeCustomerCreated($event['data']['object']);
                    break;
                case 'subscription.created':
                    $this->processStripeSubscriptionCreated($event['data']['object']);
                    break;
                default:
                    Log::info('Unhandled Stripe webhook type', ['type' => $eventType]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->getContent()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Verify Shopify webhook signature
     */
    protected function verifyShopifyWebhook(Request $request): bool
    {
        $hmac = $request->header('X-Shopify-Hmac-Sha256');
        $data = $request->getContent();
        $secret = config('integrations.shopify.webhook_secret');

        if (!$secret || !$hmac) {
            return false;
        }

        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, $secret, true));
        return hash_equals($hmac, $calculatedHmac);
    }

    /**
     * Verify Stripe webhook signature
     */
    protected function verifyStripeWebhook(string $payload, string $sigHeader, string $endpointSecret): bool
    {
        if (!$endpointSecret || !$sigHeader) {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        // Parse signature header
        foreach (explode(',', $sigHeader) as $pair) {
            $pair = explode('=', $pair, 2);
            if (count($pair) === 2) {
                if ($pair[0] === 't') {
                    $timestamp = $pair[1];
                } elseif ($pair[0] === 'v1') {
                    $signatures[] = $pair[1];
                }
            }
        }

        if (!$timestamp || empty($signatures)) {
            return false;
        }

        // Check timestamp (prevent replay attacks)
        if (abs(time() - $timestamp) > 300) { // 5 minutes
            return false;
        }

        // Verify signature
        $signedPayload = $timestamp . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $endpointSecret);

        foreach ($signatures as $signature) {
            if (hash_equals($expectedSignature, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process Shopify order update
     */
    protected function processShopifyOrderUpdate(array $data): void
    {
        $order = $data['order'] ?? [];

        // Update local order data or trigger sync
        Log::info('Processing Shopify order update', [
            'order_id' => $order['id'] ?? 'unknown',
            'status' => $order['financial_status'] ?? 'unknown'
        ]);

        // You can add custom logic here to update your local database
        // or trigger additional integrations
    }

    /**
     * Process Shopify order creation
     */
    protected function processShopifyOrderCreate(array $data): void
    {
        $order = $data['order'] ?? [];

        Log::info('Processing Shopify order creation', [
            'order_id' => $order['id'] ?? 'unknown',
            'total' => $order['total_price'] ?? 'unknown'
        ]);

        // Trigger email notifications, update inventory, etc.
    }

    /**
     * Process Shopify product update
     */
    protected function processShopifyProductUpdate(array $data): void
    {
        $product = $data['product'] ?? [];

        Log::info('Processing Shopify product update', [
            'product_id' => $product['id'] ?? 'unknown',
            'title' => $product['title'] ?? 'unknown'
        ]);

        // Sync product data to your CMS
    }

    /**
     * Process Mailchimp subscription
     */
    protected function processMailchimpSubscribe(array $data): void
    {
        $member = $data['data'] ?? [];

        Log::info('Processing Mailchimp subscription', [
            'email' => $member['email'] ?? 'unknown',
            'list_id' => $data['list_id'] ?? 'unknown'
        ]);

        // Add subscriber to your local database or trigger welcome email
    }

    /**
     * Process Mailchimp unsubscription
     */
    protected function processMailchimpUnsubscribe(array $data): void
    {
        $member = $data['data'] ?? [];

        Log::info('Processing Mailchimp unsubscription', [
            'email' => $member['email'] ?? 'unknown',
            'list_id' => $data['list_id'] ?? 'unknown'
        ]);

        // Update subscriber status in your local database
    }

    /**
     * Process Mailchimp profile update
     */
    protected function processMailchimpProfileUpdate(array $data): void
    {
        $member = $data['data'] ?? [];

        Log::info('Processing Mailchimp profile update', [
            'email' => $member['email'] ?? 'unknown',
            'list_id' => $data['list_id'] ?? 'unknown'
        ]);

        // Update subscriber profile in your local database
    }

    /**
     * Process Stripe charge succeeded
     */
    protected function processStripeChargeSucceeded(array $charge): void
    {
        Log::info('Processing Stripe charge succeeded', [
            'charge_id' => $charge['id'] ?? 'unknown',
            'amount' => $charge['amount'] ?? 'unknown',
            'customer' => $charge['customer'] ?? 'unknown'
        ]);

        // Send confirmation email, update order status, etc.
    }

    /**
     * Process Stripe charge failed
     */
    protected function processStripeChargeFailed(array $charge): void
    {
        Log::info('Processing Stripe charge failed', [
            'charge_id' => $charge['id'] ?? 'unknown',
            'amount' => $charge['amount'] ?? 'unknown',
            'customer' => $charge['customer'] ?? 'unknown'
        ]);

        // Send failure notification, retry payment, etc.
    }

    /**
     * Process Stripe customer created
     */
    protected function processStripeCustomerCreated(array $customer): void
    {
        Log::info('Processing Stripe customer created', [
            'customer_id' => $customer['id'] ?? 'unknown',
            'email' => $customer['email'] ?? 'unknown'
        ]);

        // Create customer record in your local database
    }

    /**
     * Process Stripe subscription created
     */
    protected function processStripeSubscriptionCreated(array $subscription): void
    {
        Log::info('Processing Stripe subscription created', [
            'subscription_id' => $subscription['id'] ?? 'unknown',
            'customer' => $subscription['customer'] ?? 'unknown',
            'status' => $subscription['status'] ?? 'unknown'
        ]);

        // Activate subscription in your system
    }
}
