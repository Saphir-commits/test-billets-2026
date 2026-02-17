<?php

namespace App\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Csrf;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\PricingOption;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * SubscriptionController : Handles CRUD operations for subscriptions
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class SubscriptionController
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
     * index() : Display all subscriptions (GET /subscriptions)
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
        $page_title = 'Subscriptions';
        $subscriptions = Subscription::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/subscriptions/index.php';
    }

    /**
     * user_subscriptions() : Display all subscriptions for a given user (GET /subscriptions/user/{user_id})
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $user_id User ID from the URL
     *
     * @return void
     */
    public function user_subscriptions( int $user_id ) : void
    {
        /**
         * Variables
         */
        $user = User::find( $user_id );
        $subscriptions = array(); // Rendered in view

        /**
         * Validation of user existence
         *
         * SENTINELLE
         */
        if ( $user === null )
        {
            $_SESSION['flash_error'] = 'User not found.';
            ( new RedirectResponse( '/users' ) )->send();
            exit();
        }

        /**
         * Retrieve subscriptions for this user
         */
        $subscriptions = Subscription::find_by_user( $user_id );
        $page_title = 'Subscriptions — ' . $user['name'];

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/subscriptions/user.php';
    }

    /**
     * create_form() : Display the create subscription form (GET /subscriptions/create)
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
        $page_title = 'Create Subscription';
        $users = User::all();
        $products = Product::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/subscriptions/create.php';
    }

    /**
     * create() : Process the create subscription form (POST /subscriptions/create)
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
        $user_id = (int) $this->request->request->get( 'user_id', 0 );
        $product_id = (int) $this->request->request->get( 'product_id', 0 );
        $canceled_at = trim( $this->request->request->get( 'canceled_at', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'subscriptions', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/subscriptions/create' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( $user_id <= 0 || $product_id <= 0 )
        {
            $_SESSION['flash_error'] = 'User and product are required.';
            ( new RedirectResponse( '/subscriptions/create' ) )->send();
            exit();
        }

        /**
         * Look up the product to snapshot the price
         */
        $product = Product::find( $product_id );
        $pricing_option = PricingOption::find( (int) $product['product_pricing_option_id'] );

        if ( $product === null )
        {
            $_SESSION['flash_error'] = 'The selected product was not found.';
            ( new RedirectResponse( '/subscriptions/create' ) )->send();
            exit();
        }

        if ( $pricing_option === null )
        {
            $_SESSION['flash_error'] = 'The selected pricing option was not found.';
            ( new RedirectResponse( '/subscriptions/create' ) )->send();
            exit();
        }

        /**
         * Build data and create the subscription
         */
        $data = array(
            'user_id'     => $user_id,
            'product_id'  => $product_id,
            'price'       => $product['price'],
            'expired_at'  => Carbon::now()->addDays( (int) $pricing_option['nb_days'] )->toDateTimeString(),
            'canceled_at' => null,
        );

        Subscription::create( $data );

        $_SESSION['flash_success'] = 'Subscription created successfully.';
        ( new RedirectResponse( '/subscriptions' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the edit subscription form (GET /subscriptions/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Subscription ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $subscription = Subscription::find( $id );
        $page_title = 'Edit Subscription';
        $users = User::all();
        $products = Product::all();

        /**
         * Validation of subscription existence
         *
         * SENTINELLE
         */
        if ( $subscription === null )
        {
            $_SESSION['flash_error'] = 'Subscription not found.';
            ( new RedirectResponse( '/subscriptions' ) )->send();
            exit();
        }

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/subscriptions/edit.php';
    }

    /**
     * update() : Process the edit subscription form (POST /subscriptions/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Subscription ID from the URL
     *
     * @return void
     */
    public function update( int $id ) : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $user_id = (int) $this->request->request->get( 'user_id', 0 );
        $product_id = (int) $this->request->request->get( 'product_id', 0 );
        $price = (float) $this->request->request->get( 'price', 0 );
        $expired_at = trim( $this->request->request->get( 'expired_at', '' ) );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'subscriptions', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/subscriptions/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( $user_id <= 0 || $product_id <= 0 || empty( $expired_at ) )
        {
            $_SESSION['flash_error'] = 'User, product and expiration date are required.';
            ( new RedirectResponse( '/subscriptions/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Build data and update the subscription
         */
        $data = array(
            'user_id'     => $user_id,
            'product_id'  => $product_id,
            'price'       => $price,
            'expired_at'  => $expired_at,
        );

        Subscription::update( $id, $data );

        $_SESSION['flash_success'] = 'Subscription updated successfully.';
        ( new RedirectResponse( '/subscriptions' ) )->send();
        exit();
    }

    /**
     * cancel() : Cancel a subscription (POST /subscriptions/{id}/cancel)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Subscription ID from the URL
     *
     * @return void
     */
    public function cancel( int $id ) : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $redirect_to = trim( $this->request->request->get( 'redirect_to', '' ) );
        $redirect_url = '/subscriptions'; // Valeur de retour

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'subscriptions', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/subscriptions' ) )->send();
            exit();
        }

        /**
         * Use custom redirect if it is a safe internal path
         */
        if ( ! empty( $redirect_to ) && str_starts_with( $redirect_to, '/' ) )
            $redirect_url = $redirect_to;

        /**
         * Cancel the subscription
         */
        $data = array(
            'canceled_at' => Carbon::now(),
        );

        Subscription::update( $id, $data );

        $_SESSION['flash_success'] = 'Subscription canceled successfully.';
        ( new RedirectResponse( $redirect_url ) )->send();
        exit();
    }

    /**
     * product_pricing() : Return pricing data for a product as JSON (GET /products/{id}/pricing)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $product_id Product ID from the URL
     *
     * @return void
     */
    public function product_pricing( int $product_id ) : void
    {
        /**
         * Variables
         */
        $product = null;
        $pricing_option = null;

        /**
         * Validation of product ID
         *
         * SENTINELLE
         */
        if ( $product_id <= 0 )
        {
            header( 'Content-Type: application/json' );
            http_response_code( 400 );
            echo json_encode( array( 'error' => 'Invalid product ID' ) );
            exit();
        }

        /**
         * Retrieve product and its pricing option
         */
        $product = Product::find( $product_id );

        if ( $product === null )
        {
            header( 'Content-Type: application/json' );
            http_response_code( 404 );
            echo json_encode( array( 'error' => 'Product not found' ) );
            exit();
        }

        $pricing_option = PricingOption::find( (int) $product['product_pricing_option_id'] );

        if ( $pricing_option === null )
        {
            header( 'Content-Type: application/json' );
            http_response_code( 404 );
            echo json_encode( array( 'error' => 'Pricing option not found' ) );
            exit();
        }

        /**
         * Succès
         */
        header( 'Content-Type: application/json' );
        echo json_encode( array(
            'nb_days' => (int) $pricing_option['nb_days'],
            'price'   => (float) $product['price'],
        ) );
        exit();
    }

    /**
     * delete() : Delete a subscription (POST /subscriptions/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Subscription ID from the URL
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
        if ( ! Csrf::validate( 'subscriptions', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/subscriptions' ) )->send();
            exit();
        }

        /**
         * Delete the subscription
         */
        Subscription::delete( $id );

        $_SESSION['flash_success'] = 'Subscription deleted successfully.';
        ( new RedirectResponse( '/subscriptions' ) )->send();
        exit();
    }
}
