<?php

namespace App\Controllers;

use App\Models\PricingOption;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * PricingOptionController : Handles CRUD operations for pricing options
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class PricingOptionController
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
     * index() : Display the list of all pricing options (GET /pricing-options)
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
        $page_title = 'Pricing Options';
        $pricing_options = PricingOption::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/pricing_options/index.php';
    }

    /**
     * create_form() : Display the create pricing option form (GET /pricing-options/create)
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
        $page_title = 'Create Pricing Option';

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/pricing_options/create.php';
    }

    /**
     * create() : Process the create pricing option form (POST /pricing-options/create)
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
        $nb_days = (int) $this->request->request->get( 'nb_days', 0 );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'pricing_options', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/pricing-options/create' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $name ) || $nb_days <= 0 )
        {
            $_SESSION['flash_error'] = 'Name and a valid number of days are required.';
            ( new RedirectResponse( '/pricing-options/create' ) )->send();
            exit();
        }

        /**
         * Create the pricing option
         */
        PricingOption::create( array(
            'name'    => $name,
            'nb_days' => $nb_days,
        ) );

        $_SESSION['flash_success'] = 'Pricing option created successfully.';
        ( new RedirectResponse( '/pricing-options' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the edit pricing option form (GET /pricing-options/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Pricing option ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $pricing_option = PricingOption::find( $id );

        /**
         * Validation of pricing option existence
         *
         * SENTINELLE
         */
        if ( $pricing_option === null )
        {
            $_SESSION['flash_error'] = 'Pricing option not found.';
            ( new RedirectResponse( '/pricing-options' ) )->send();
            exit();
        }

        /**
         * Render the view
         */
        $page_title = 'Edit Pricing Option';
        require __DIR__ . '/../../ressources/views/pricing_options/edit.php';
    }

    /**
     * update() : Process the edit pricing option form (POST /pricing-options/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Pricing option ID from the URL
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
        $nb_days = (int) $this->request->request->get( 'nb_days', 0 );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'pricing_options', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/pricing-options/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $name ) || $nb_days <= 0 )
        {
            $_SESSION['flash_error'] = 'Name and a valid number of days are required.';
            ( new RedirectResponse( '/pricing-options/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Update the pricing option
         */
        PricingOption::update( $id, array(
            'name'    => $name,
            'nb_days' => $nb_days,
        ) );

        $_SESSION['flash_success'] = 'Pricing option updated successfully.';
        ( new RedirectResponse( '/pricing-options' ) )->send();
        exit();
    }

    /**
     * delete() : Delete a pricing option (POST /pricing-options/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Pricing option ID from the URL
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
        if ( ! Csrf::validate( 'pricing_options', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/pricing-options' ) )->send();
            exit();
        }

        /**
         * Delete the pricing option
         */
        PricingOption::delete( $id );

        $_SESSION['flash_success'] = 'Pricing option deleted successfully.';
        ( new RedirectResponse( '/pricing-options' ) )->send();
        exit();
    }
}
