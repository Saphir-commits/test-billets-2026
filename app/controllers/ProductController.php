<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\PricingOption;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ProductController : Handles CRUD operations for products
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ProductController
{
    /**
     * Attributes
     */
    protected Request $request;

    /**
     * __construct() : Inject the HTTP request
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param Request $request Current HTTP request
     */
    public function __construct( Request $request )
    {
        /**
         * Initialisation
         */
        $this->request = $request;
    }

    /**
     * index() : Display the list of all products (GET /products)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function index() : void
    {
        /**
         * Variables
         */
        $products = Product::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products/index.php';
    }

    /**
     * create_form() : Display the product creation form (GET /products/create)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function create_form() : void
    {
        /**
         * Variables
         */
        $product_types = ProductType::all();
        $pricing_options = PricingOption::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products/create.php';
    }

    /**
     * create() : Process product creation form submission (POST /products/create)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function create() : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $product_type_id = (int) $this->request->request->get( 'product_type_id', 0 );
        $product_pricing_option_id = (int) $this->request->request->get( 'product_pricing_option_id', 0 );
        $price = trim( $this->request->request->get( 'price', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'products', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products/create' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $product_type_id ) || empty( $product_pricing_option_id ) || $price === '' )
        {
            $_SESSION['flash_error'] = 'Product type, pricing option and price are required.';
            ( new RedirectResponse( '/products/create' ) )->send();
            exit();
        }

        /**
         * Create the product
         */
        $data = array(
            'product_type_id' => $product_type_id,
            'product_pricing_option_id' => $product_pricing_option_id,
            'price' => (float) $price
        );

        Product::create( $data );

        $_SESSION['flash_success'] = 'Product created successfully.';
        ( new RedirectResponse( '/products' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the product edit form (GET /products/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $product = Product::find( $id );

        /**
         * Validation of product existence
         *
         * SENTINELLE
         */
        if ( $product === null )
        {
            $_SESSION['flash_error'] = 'Product not found.';
            ( new RedirectResponse( '/products' ) )->send();
            exit();
        }

        /**
         * Load foreign key lists
         */
        $product_types = ProductType::all();
        $pricing_options = PricingOption::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products/edit.php';
    }

    /**
     * update() : Process product update form submission (POST /products/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product ID from the URL
     *
     * @return void
     */
    public function update( int $id ) : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $product_type_id = (int) $this->request->request->get( 'product_type_id', 0 );
        $product_pricing_option_id = (int) $this->request->request->get( 'product_pricing_option_id', 0 );
        $price = trim( $this->request->request->get( 'price', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'products', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $product_type_id ) || empty( $product_pricing_option_id ) || $price === '' )
        {
            $_SESSION['flash_error'] = 'Product type, pricing option and price are required.';
            ( new RedirectResponse( '/products/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Update the product
         */
        $data = array(
            'product_type_id' => $product_type_id,
            'product_pricing_option_id' => $product_pricing_option_id,
            'price' => (float) $price
        );

        Product::update( $id, $data );

        $_SESSION['flash_success'] = 'Product updated successfully.';
        ( new RedirectResponse( '/products' ) )->send();
        exit();
    }

    /**
     * delete() : Delete a product (POST /products/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product ID from the URL
     *
     * @return void
     */
    public function delete( int $id ) : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'products', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products' ) )->send();
            exit();
        }

        /**
         * Delete the product
         */
        Product::delete( $id );

        $_SESSION['flash_success'] = 'Product deleted successfully.';
        ( new RedirectResponse( '/products' ) )->send();
        exit();
    }
}
