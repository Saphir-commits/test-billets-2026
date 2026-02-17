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
 * ExpiredAtTest : Tests for the expired_at calculation on subscription creation
 *
 * Covers: expired_at = now + nb_days from the product's pricing option.
 * Default fixture uses a 30-day pricing option.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ExpiredAtTest extends TestCase
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
     * setUp() : Create a 30-day fixture chain before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'test_expiry_role' ) );

        $this->user_id = User::create( array(
            'name'    => 'Expiry Test User',
            'email'   => 'expiry_' . uniqid( '', true ) . '@test.local',
            'password' => 'password',
            'role_id' => $this->role_id,
        ) );

        $this->product_type_id = ProductType::create( array( 'name' => 'Test Expiry Type' ) );

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Test 30-Day',
            'nb_days' => 30,
        ) );

        $this->product_id = Product::create( array(
            'product_type_id'           => $this->product_type_id,
            'product_pricing_option_id' => $this->pricing_option_id,
            'price'                     => 9.99,
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
     * test_expired_at_is_in_the_future() : expired_at must be a future date
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_expired_at_is_in_the_future() : void
    {
        $pricing_option = PricingOption::find( $this->pricing_option_id );
        $expired_at = Carbon::now()->addDays( (int) $pricing_option['nb_days'] )->toDateTimeString();

        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 9.99,
            'expired_at' => $expired_at,
            'canceled_at' => null,
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertGreaterThan( time(), strtotime( $subscription['expired_at'] ) );
    }

    /**
     * test_expired_at_matches_nb_days_from_now() : expired_at is approximately now + nb_days
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_expired_at_matches_nb_days_from_now() : void
    {
        $pricing_option = PricingOption::find( $this->pricing_option_id );
        $expected_expiry = Carbon::now()->addDays( (int) $pricing_option['nb_days'] )->toDateTimeString();

        $this->subscription_id = Subscription::create( array(
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'price'      => 9.99,
            'expired_at' => $expected_expiry,
            'canceled_at' => null,
        ) );

        $subscription = Subscription::find( $this->subscription_id );

        $this->assertEqualsWithDelta(
            strtotime( $expected_expiry ),
            strtotime( $subscription['expired_at'] ),
            60,
            'expired_at should be approximately now + 30 days'
        );
    }

    /**
     * test_expired_at_differs_by_pricing_option() : Different nb_days produce different expired_at values
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_expired_at_differs_by_pricing_option() : void
    {
        /**
         * Variables — second fixture set (365-day pricing)
         */
        $sub_30d_id = 0;
        $sub_365d_id = 0;
        $role_id_2 = 0;
        $user_id_2 = 0;
        $type_id_2 = 0;
        $option_id_2 = 0;
        $product_id_2 = 0;

        try
        {
            $option_30 = PricingOption::find( $this->pricing_option_id );
            $sub_30d_id = Subscription::create( array(
                'user_id'    => $this->user_id,
                'product_id' => $this->product_id,
                'price'      => 9.99,
                'expired_at' => Carbon::now()->addDays( (int) $option_30['nb_days'] )->toDateTimeString(),
                'canceled_at' => null,
            ) );

            $role_id_2   = Role::create( array( 'name' => 'test_expiry_role_2' ) );
            $user_id_2   = User::create( array(
                'name'    => 'Expiry Test User 2',
                'email'   => 'expiry2_' . uniqid( '', true ) . '@test.local',
                'password' => 'password',
                'role_id' => $role_id_2,
            ) );
            $type_id_2   = ProductType::create( array( 'name' => 'Test Expiry Type 2' ) );
            $option_id_2 = PricingOption::create( array( 'name' => 'Test 365-Day', 'nb_days' => 365 ) );
            $product_id_2 = Product::create( array(
                'product_type_id'           => $type_id_2,
                'product_pricing_option_id' => $option_id_2,
                'price'                     => 9.99,
            ) );

            $option_365 = PricingOption::find( $option_id_2 );
            $sub_365d_id = Subscription::create( array(
                'user_id'    => $user_id_2,
                'product_id' => $product_id_2,
                'price'      => 9.99,
                'expired_at' => Carbon::now()->addDays( (int) $option_365['nb_days'] )->toDateTimeString(),
                'canceled_at' => null,
            ) );

            $sub_30  = Subscription::find( $sub_30d_id );
            $sub_365 = Subscription::find( $sub_365d_id );

            $this->assertGreaterThan(
                strtotime( $sub_30['expired_at'] ),
                strtotime( $sub_365['expired_at'] ),
                'A 365-day subscription should expire later than a 30-day one'
            );
        }
        finally
        {
            if ( $sub_30d_id > 0 )   Subscription::delete( $sub_30d_id );
            if ( $sub_365d_id > 0 )  Subscription::delete( $sub_365d_id );
            if ( $product_id_2 > 0 ) Product::delete( $product_id_2 );
            if ( $option_id_2 > 0 )  PricingOption::delete( $option_id_2 );
            if ( $type_id_2 > 0 )    ProductType::delete( $type_id_2 );
            if ( $user_id_2 > 0 )    User::delete( $user_id_2 );
            if ( $role_id_2 > 0 )    Role::delete( $role_id_2 );
        }
    }
}
