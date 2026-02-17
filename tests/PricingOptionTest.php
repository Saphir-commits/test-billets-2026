<?php

namespace Tests;

use App\Models\PricingOption;
use PHPUnit\Framework\TestCase;

/**
 * PricingOptionTest : Tests for PricingOption CRUD operations
 *
 * Covers: PricingOption::create(), find(), update(), delete(), all()
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class PricingOptionTest extends TestCase
{
    /**
     * Attributes
     */
    private int $pricing_option_id = 0;

    /**
     * setUp() : Create a test pricing option (30 days) before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Test Monthly',
            'nb_days' => 30,
        ) );
    }

    /**
     * tearDown() : Delete the test pricing option after each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function tearDown() : void
    {
        if ( $this->pricing_option_id > 0 )
            PricingOption::delete( $this->pricing_option_id );

        parent::tearDown();
    }

    /**
     * test_create_returns_positive_id() : create() returns a positive integer ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_create_returns_positive_id() : void
    {
        $this->assertGreaterThan( 0, $this->pricing_option_id );
    }

    /**
     * test_find_returns_correct_nb_days() : find() retrieves the pricing option with the correct nb_days
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_nb_days() : void
    {
        $option = PricingOption::find( $this->pricing_option_id );

        $this->assertNotNull( $option );
        $this->assertSame( 30, (int) $option['nb_days'] );
    }

    /**
     * test_find_returns_correct_name() : find() retrieves the pricing option with the correct name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_name() : void
    {
        $option = PricingOption::find( $this->pricing_option_id );

        $this->assertNotNull( $option );
        $this->assertSame( 'Test Monthly', $option['name'] );
    }

    /**
     * test_update_changes_nb_days() : update() changes nb_days
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_nb_days() : void
    {
        PricingOption::update( $this->pricing_option_id, array( 'nb_days' => 365 ) );

        $option = PricingOption::find( $this->pricing_option_id );

        $this->assertSame( 365, (int) $option['nb_days'] );
    }

    /**
     * test_update_changes_name() : update() changes the pricing option name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_name() : void
    {
        PricingOption::update( $this->pricing_option_id, array( 'name' => 'Updated Yearly' ) );

        $option = PricingOption::find( $this->pricing_option_id );

        $this->assertSame( 'Updated Yearly', $option['name'] );
    }

    /**
     * test_delete_removes_record() : delete() makes find() return null
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_delete_removes_record() : void
    {
        PricingOption::delete( $this->pricing_option_id );

        $option = PricingOption::find( $this->pricing_option_id );

        $this->assertNull( $option );

        $this->pricing_option_id = 0;
    }

    /**
     * test_all_contains_created_option() : all() includes the newly created pricing option
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_all_contains_created_option() : void
    {
        $options = PricingOption::all();
        $ids     = array_map( 'intval', array_column( $options, 'id' ) );

        $this->assertContains( $this->pricing_option_id, $ids );
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
        $this->assertNull( PricingOption::find( 0 ) );
    }
}
