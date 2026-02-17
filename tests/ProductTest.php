<?php

namespace Tests;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\PricingOption;
use PHPUnit\Framework\TestCase;

/**
 * ProductTest : Tests for Product CRUD operations
 *
 * Covers: Product::create(), find(), update(), delete(), all()
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ProductTest extends TestCase
{
    /**
     * Attributes
     */
    private int $product_type_id = 0;
    private int $pricing_option_id = 0;
    private int $product_id = 0;

    /**
     * setUp() : Create a product type, pricing option, and product before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->product_type_id = ProductType::create( array( 'name' => 'Test Product Type' ) );

        $this->pricing_option_id = PricingOption::create( array(
            'name'    => 'Test Monthly',
            'nb_days' => 30,
        ) );

        $this->product_id = Product::create( array(
            'product_type_id'           => $this->product_type_id,
            'product_pricing_option_id' => $this->pricing_option_id,
            'price'                     => 19.99,
        ) );
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
        if ( $this->product_id > 0 )
            Product::delete( $this->product_id );

        if ( $this->pricing_option_id > 0 )
            PricingOption::delete( $this->pricing_option_id );

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
        $this->assertGreaterThan( 0, $this->product_id );
    }

    /**
     * test_find_returns_correct_price() : find() retrieves the product with the correct price
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_price() : void
    {
        $product = Product::find( $this->product_id );

        $this->assertNotNull( $product );
        $this->assertSame( 19.99, (float) $product['price'] );
    }

    /**
     * test_find_returns_correct_type_and_option() : find() retrieves the correct foreign key IDs
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_type_and_option() : void
    {
        $product = Product::find( $this->product_id );

        $this->assertSame( $this->product_type_id, (int) $product['product_type_id'] );
        $this->assertSame( $this->pricing_option_id, (int) $product['product_pricing_option_id'] );
    }

    /**
     * test_update_changes_price() : update() changes the product price
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_price() : void
    {
        Product::update( $this->product_id, array( 'price' => 49.99 ) );

        $product = Product::find( $this->product_id );

        $this->assertSame( 49.99, (float) $product['price'] );
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
        Product::delete( $this->product_id );

        $product = Product::find( $this->product_id );

        $this->assertNull( $product );

        $this->product_id = 0;
    }

    /**
     * test_all_contains_created_product() : all() includes the newly created product
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_all_contains_created_product() : void
    {
        $products = Product::all();
        $ids      = array_map( 'intval', array_column( $products, 'id' ) );

        $this->assertContains( $this->product_id, $ids );
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
        $this->assertNull( Product::find( 0 ) );
    }
}
