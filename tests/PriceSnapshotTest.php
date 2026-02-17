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
 * PriceSnapshotTest : Tests for price snapshot integrity on subscriptions
 *
 * Covers: The subscription price is snapshotted from the product at creation time
 * and must remain unchanged even if the product price is later updated.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class PriceSnapshotTest extends TestCase
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
     * setUp() : Create the full dependency chain before each test (price 29.99)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'test_snapshot_role' ) );

        $this->user_id = User::create( array(
            'name'    => 'Snapshot Test User',
            'email'   => 'snapshot_' . uniqid( '', true ) . '@test.local',
            'password' => 'password',
            'role_id' => $this->role_id,
        ) );

        $this->product_type_id = ProductType::create( array( 'name' => 'Snapshot Test Type' ) );

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Snapshot Monthly',
            'nb_days' => 30,
        ) );

        $this->product_id = Product::create( array(
            'product_type_id'           => $this->product_type_id,
            'product_pricing_option_id' => $this->pricing_option_id,
            'price'                     => 29.99,
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
     * test_subscription_price_matches_product_price_at_creation() : Snapshot equals product price at creation
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_subscription_price_matches_product_price_at_creation() : void
    {
        $product = Product::find( $this->product_id );

        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => (float) $product['price'],
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertSame( 29.99, (float) $subscription['price'] );
    }

    /**
     * test_subscription_price_unchanged_after_product_price_update() : Snapshot is independent from product changes
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_subscription_price_unchanged_after_product_price_update() : void
    {
        $product = Product::find( $this->product_id );

        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => (float) $product['price'],
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        /**
         * Update the product price AFTER subscription was created
         */
        Product::update( $this->product_id, array( 'price' => 59.99 ) );

        $subscription    = Subscription::find( $this->subscription_id );
        $updated_product = Product::find( $this->product_id );

        $this->assertSame( 59.99, (float) $updated_product['price'], 'Product price should have been updated to 59.99' );
        $this->assertSame( 29.99, (float) $subscription['price'], 'Subscription price should remain 29.99 (snapshot)' );
    }

    /**
     * test_two_subscriptions_to_same_product_can_have_different_prices() : Each subscription snapshots independently
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_two_subscriptions_to_same_product_can_have_different_prices() : void
    {
        /**
         * Variables
         */
        $sub_1_id = 0;
        $sub_2_id = 0;

        try
        {
            $product = Product::find( $this->product_id );

            $sub_1_id = Subscription::create( array(
                'user_id'    => $this->user_id,
                'product_id' => $this->product_id,
                'price'      => (float) $product['price'],
                'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
                'canceled_at' => null,
            ) );

            Product::update( $this->product_id, array( 'price' => 39.99 ) );
            $product = Product::find( $this->product_id );

            $sub_2_id = Subscription::create( array(
                'user_id'    => $this->user_id,
                'product_id' => $this->product_id,
                'price'      => (float) $product['price'],
                'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
                'canceled_at' => null,
            ) );

            $sub_1 = Subscription::find( $sub_1_id );
            $sub_2 = Subscription::find( $sub_2_id );

            $this->assertSame( 29.99, (float) $sub_1['price'], 'First subscription should have the original price (29.99)' );
            $this->assertSame( 39.99, (float) $sub_2['price'], 'Second subscription should have the updated price (39.99)' );
        }
        finally
        {
            if ( $sub_1_id > 0 ) Subscription::delete( $sub_1_id );
            if ( $sub_2_id > 0 ) Subscription::delete( $sub_2_id );
        }
    }
}
