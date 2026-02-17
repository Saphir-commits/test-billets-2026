<?php

namespace App\Controllers;

use App\Models\ProductType;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ProductTypeController : Handles CRUD operations for product types
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ProductTypeController
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
     * index() : Display all product types (GET /products-types)
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
        $page_title = 'Product Types';
        $product_types = ProductType::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products_types/index.php';
    }

    /**
     * create_form() : Display the create product type form (GET /products-types/create)
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
        $page_title = 'Create Product Type';

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products_types/create.php';
    }

    /**
     * create() : Process the create product type form (POST /products-types/create)
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
        $name = trim( $this->request->request->get( 'name', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'products_types', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products-types/create' ) )->send();
            exit();
        }

        /**
         * Validation of name
         *
         * SENTINELLE
         */
        if ( empty( $name ) )
        {
            $_SESSION['flash_error'] = 'The name field is required.';
            ( new RedirectResponse( '/products-types/create' ) )->send();
            exit();
        }

        /**
         * Create the product type
         */
        ProductType::create( array( 'name' => $name ) );

        $_SESSION['flash_success'] = 'Product type created successfully.';
        ( new RedirectResponse( '/products-types' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the edit product type form (GET /products-types/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product type ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $product_type = ProductType::find( $id );
        $page_title = 'Edit Product Type';

        /**
         * Validation of product type existence
         *
         * SENTINELLE
         */
        if ( $product_type === null )
        {
            $_SESSION['flash_error'] = 'Product type not found.';
            ( new RedirectResponse( '/products-types' ) )->send();
            exit();
        }

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/products_types/edit.php';
    }

    /**
     * update() : Process the edit product type form (POST /products-types/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product type ID from the URL
     *
     * @return void
     */
    public function update( int $id ) : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $name = trim( $this->request->request->get( 'name', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'products_types', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products-types/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of name
         *
         * SENTINELLE
         */
        if ( empty( $name ) )
        {
            $_SESSION['flash_error'] = 'The name field is required.';
            ( new RedirectResponse( '/products-types/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Update the product type
         */
        ProductType::update( $id, array( 'name' => $name ) );

        $_SESSION['flash_success'] = 'Product type updated successfully.';
        ( new RedirectResponse( '/products-types' ) )->send();
        exit();
    }

    /**
     * delete() : Delete a product type (POST /products-types/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Product type ID from the URL
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
        if ( ! Csrf::validate( 'products_types', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/products-types' ) )->send();
            exit();
        }

        /**
         * Delete the product type
         */
        ProductType::delete( $id );

        $_SESSION['flash_success'] = 'Product type deleted successfully.';
        ( new RedirectResponse( '/products-types' ) )->send();
        exit();
    }
}
