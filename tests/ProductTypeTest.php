<?php

namespace Tests;

use App\Models\ProductType;
use PHPUnit\Framework\TestCase;

/**
 * ProductTypeTest : Tests for ProductType CRUD operations
 *
 * Covers: ProductType::create(), find(), update(), delete(), all()
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ProductTypeTest extends TestCase
{
    /**
     * Attributes
     */
    private int $product_type_id = 0;

    /**
     * setUp() : Create a test product type before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->product_type_id = ProductType::create( array( 'name' => 'Test Type' ) );
    }

    /**
     * tearDown() : Delete the test product type after each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function tearDown() : void
    {
        if ( $this->product_type_id > 0 )
            ProductType::delete( $this->product_type_id );

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
        $this->assertGreaterThan( 0, $this->product_type_id );
    }

    /**
     * test_find_returns_correct_name() : find() retrieves the product type with the correct name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_name() : void
    {
        $type = ProductType::find( $this->product_type_id );

        $this->assertNotNull( $type );
        $this->assertSame( 'Test Type', $type['name'] );
    }

    /**
     * test_update_changes_name() : update() changes the product type name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_name() : void
    {
        ProductType::update( $this->product_type_id, array( 'name' => 'Updated Type' ) );

        $type = ProductType::find( $this->product_type_id );

        $this->assertSame( 'Updated Type', $type['name'] );
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
        ProductType::delete( $this->product_type_id );

        $type = ProductType::find( $this->product_type_id );

        $this->assertNull( $type );

        $this->product_type_id = 0;
    }

    /**
     * test_all_contains_created_type() : all() includes the newly created product type
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_all_contains_created_type() : void
    {
        $types = ProductType::all();
        $ids   = array_map( 'intval', array_column( $types, 'id' ) );

        $this->assertContains( $this->product_type_id, $ids );
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
        $this->assertNull( ProductType::find( 0 ) );
    }
}
