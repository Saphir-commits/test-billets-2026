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
 * SubscriptionCreateTest : Tests for subscription creation business logic
 *
 * Covers: Subscription::create(), field integrity at creation time,
 * and cancellation behavior.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class SubscriptionCreateTest extends TestCase
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
     * setUp() : Create the full dependency chain before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'test_sub_role' ) );

        $this->user_id = User::create( array(
            'name'    => 'Sub Test User',
            'email'   => 'sub_' . uniqid( '', true ) . '@test.local',
            'password' => 'password',
            'role_id' => $this->role_id,
        ) );

        $this->product_type_id = ProductType::create( array( 'name' => 'Test Type' ) );

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Test Monthly',
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
     * test_create_returns_positive_id() : Subscription::create() returns a positive integer ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_create_returns_positive_id() : void
    {
        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 19.99,
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        $this->assertGreaterThan( 0, $this->subscription_id );
    }

    /**
     * test_created_subscription_has_correct_user_and_product() : Subscription is linked to the right user and product
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_created_subscription_has_correct_user_and_product() : void
    {
        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 19.99,
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertSame( $this->user_id, (int) $subscription['user_id'] );
        $this->assertSame( $this->product_id, (int) $subscription['product_id'] );
    }

    /**
     * test_created_subscription_canceled_at_is_null() : canceled_at is null on a fresh subscription
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_created_subscription_canceled_at_is_null() : void
    {
        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 19.99,
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertNull( $subscription['canceled_at'] );
    }

    /**
     * test_cancel_sets_canceled_at() : Canceling a subscription sets canceled_at to a non-null datetime
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_cancel_sets_canceled_at() : void
    {
        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 19.99,
            'expired_at' => Carbon::now()->addDays( 30 )->toDateTimeString(),
            'canceled_at' => null,
        ) );

        Subscription::update( $this->subscription_id, array(
            'canceled_at' => Carbon::now()->toDateTimeString(),
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertNotNull( $subscription['canceled_at'] );
    }

    /**
     * test_find_returns_null_for_invalid_id() : find() returns null for ID <= 0
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_null_for_invalid_id() : void
    {
        $this->assertNull( Subscription::find( 0 ) );
    }
}
