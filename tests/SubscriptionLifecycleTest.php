<?php

namespace Tests;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\PricingOption;
use App\Models\Subscription;
use PHPUnit\Framework\TestCase;

/**
 * SubscriptionLifecycleTest : End-to-end test for the full subscription lifecycle
 *
 * Covers: The complete flow — create user, create product, subscribe,
 * cancel, and verify that the subscription remains active but will not renew.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class SubscriptionLifecycleTest extends TestCase
{
    /**
     * Attributes
     */
    private int $role_id = 0;
    private int $user_id = 0;
    private int $product_type_id = 0;
    private int $pricing_option_id = 0;
    private int $product_id = 0;
    private int $subscription_id = 0;

    /**
     * setUp() : Create the full dependency chain before the test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'lifecycle_role' ) );

        $this->user_id = User::create( array(
            'name'     => 'Lifecycle Test User',
            'email'    => 'lifecycle_' . uniqid( '', true ) . '@test.local',
            'password' => 'password',
            'role_id'  => $this->role_id,
        ) );

        $this->product_type_id = ProductType::create( array( 'name' => 'Lifecycle Type' ) );

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Lifecycle Monthly',
            'nb_days' => 30,
        ) );

        $this->product_id = Product::create( array(
            'product_type_id'           => $this->product_type_id,
            'product_pricing_option_id' => $this->pricing_option_id,
            'price'                     => 19.99,
        ) );

        $this->subscription_id = 0;
    }

    /**
     * tearDown() : Delete all fixtures in reverse dependency order
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function tearDown() : void
    {
        if ( $this->subscription_id > 0 )
            Subscription::delete( $this->subscription_id );

        if ( $this->product_id > 0 )
            Product::delete( $this->product_id );

        if ( $this->pricing_option_id > 0 )
            PricingOption::delete( $this->pricing_option_id );

        if ( $this->product_type_id > 0 )
            ProductType::delete( $this->product_type_id );

        if ( $this->user_id > 0 )
            User::delete( $this->user_id );

        if ( $this->role_id > 0 )
            Role::delete( $this->role_id );

        parent::tearDown();
    }

    /**
     * test_full_subscription_lifecycle() : Full lifecycle — subscribe, cancel, verify active but not renewing
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_full_subscription_lifecycle() : void
    {
        /**
         * Step 1 : Create the subscription
         */
        $product = Product::find( $this->product_id );

        $this->subscription_id = Subscription::create( array(
            'user_id'     => $this->user_id,
            'product_id'  => $this->product_id,
            'price'       => (float) $product['price'],
            'expired_at'  => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        $this->assertGreaterThan( 0, $this->subscription_id, 'Subscription must be created with a valid ID' );

        /**
         * Step 2 : Verify the subscription is active and will renew
         */
        $this->assertTrue(
            Subscription::is_active( $this->subscription_id ),
            'Subscription should be active immediately after creation'
        );

        $this->assertTrue(
            Subscription::will_renew( $this->subscription_id ),
            'Subscription should be set to renew when not yet canceled'
        );

        /**
         * Step 3 : Cancel the subscription
         */
        Subscription::update( $this->subscription_id, array(
            'canceled_at' => Carbon::now()->toDateTimeString(),
        ) );

        /**
         * Step 4 : Verify the subscription is still active but will not renew
         */
        $this->assertTrue(
            Subscription::is_active( $this->subscription_id ),
            'Subscription should remain active until expired_at even after cancellation'
        );

        $this->assertFalse(
            Subscription::will_renew( $this->subscription_id ),
            'Subscription should not renew after cancellation'
        );
    }
}
