<?php

/**
 * Front Controller
 *
 * All requests are routed through this file.
 *
 * @since 2026
 * @author Samuelle Langlois
 */

/**
 * Bootstrap
 */
require __DIR__ . '/../vendor/autoload.php';


use Carbon\Carbon;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use App\Controllers\AuthController;
use App\Controllers\RoleController;
use App\Controllers\UserController;
use App\Controllers\ProductTypeController;
use App\Controllers\PricingOptionController;
use App\Controllers\ProductController;
use App\Controllers\SubscriptionController;
use App\Helpers\Auth;

/**
 * Load environment variables
 */
$dotenv = Dotenv::createImmutable( __DIR__ . '/..' );
$dotenv->load();

/**
 * Configure timezone
 */
$timezone = $_ENV['APP_TIMEZONE'] ?? 'UTC';
date_default_timezone_set( $timezone );

/**
 * Start session
 */
session_start();

/**
 * Build request from globals
 */
$request = Request::createFromGlobals();

/**
 * Parse request
 */
$request_uri = $request->getPathInfo();
$request_method = $request->getMethod();
$matches = array();

/**
 * Controllers
 */
$auth_controller = new AuthController( $request );
$role_controller = new RoleController( $request );
$user_controller = new UserController( $request );
$product_type_controller = new ProductTypeController( $request );
$pricing_option_controller = new PricingOptionController( $request );
$product_controller = new ProductController( $request );
$subscription_controller = new SubscriptionController( $request );

/**
 * Routes
 */
switch ( true )
{
    /**
     * Authentication routes
     */
    case $request_uri === '/login' && $request_method === 'GET':
        $auth_controller->login_form();
        break;

    case $request_uri === '/login' && $request_method === 'POST':
        $auth_controller->login();
        break;

    case $request_uri === '/logout' && $request_method === 'GET':
        $auth_controller->logout();
        break;

    /**
     * Dashboard
     */
    case $request_uri === '/' && $request_method === 'GET':
        Auth::require_auth();
        $page_title = 'Dashboard';
        require __DIR__ . '/../ressources/views/dashboard.php';
        break;

    /**
     * Roles routes
     */
    case $request_uri === '/roles' && $request_method === 'GET':
        Auth::require_auth();
        $role_controller->index();
        break;

    case $request_uri === '/roles/create' && $request_method === 'GET':
        Auth::require_admin();
        $role_controller->create_form();
        break;

    case $request_uri === '/roles/create' && $request_method === 'POST':
        Auth::require_admin();
        $role_controller->create();
        break;

    case preg_match( '#^/roles/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_auth();
        $role_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/roles/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_auth();
        $role_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/roles/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $role_controller->delete( (int) $matches[1] );
        break;

    /**
     * Users routes
     */
    case $request_uri === '/users' && $request_method === 'GET':
        Auth::require_auth();
        $user_controller->index();
        break;

    case $request_uri === '/users/create' && $request_method === 'GET':
        Auth::require_auth();
        $user_controller->create_form();
        break;

    case $request_uri === '/users/create' && $request_method === 'POST':
        Auth::require_auth();
        $user_controller->create();
        break;

    case preg_match( '#^/users/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_admin();
        $user_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/users/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $user_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/users/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $user_controller->delete( (int) $matches[1] );
        break;

    /**
     * Product Types routes
     */
    case $request_uri === '/products-types' && $request_method === 'GET':
        Auth::require_auth();
        $product_type_controller->index();
        break;

    case $request_uri === '/products-types/create' && $request_method === 'GET':
        Auth::require_admin();
        $product_type_controller->create_form();
        break;

    case $request_uri === '/products-types/create' && $request_method === 'POST':
        Auth::require_admin();
        $product_type_controller->create();
        break;

    case preg_match( '#^/products-types/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_admin();
        $product_type_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/products-types/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $product_type_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/products-types/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $product_type_controller->delete( (int) $matches[1] );
        break;

    /**
     * Pricing Options routes
     */
    case $request_uri === '/pricing-options' && $request_method === 'GET':
        Auth::require_auth();
        $pricing_option_controller->index();
        break;

    case $request_uri === '/pricing-options/create' && $request_method === 'GET':
        Auth::require_admin();
        $pricing_option_controller->create_form();
        break;

    case $request_uri === '/pricing-options/create' && $request_method === 'POST':
        Auth::require_admin();
        $pricing_option_controller->create();
        break;

    case preg_match( '#^/pricing-options/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_admin();
        $pricing_option_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/pricing-options/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $pricing_option_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/pricing-options/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $pricing_option_controller->delete( (int) $matches[1] );
        break;

    /**
     * Products routes
     */
    case $request_uri === '/products' && $request_method === 'GET':
        Auth::require_auth();
        $product_controller->index();
        break;

    case $request_uri === '/products/create' && $request_method === 'GET':
        Auth::require_auth();
        $product_controller->create_form();
        break;

    case $request_uri === '/products/create' && $request_method === 'POST':
        Auth::require_auth();
        $product_controller->create();
        break;

    case preg_match( '#^/products/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_admin();
        $product_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/products/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $product_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/products/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $product_controller->delete( (int) $matches[1] );
        break;

    case preg_match( '#^/products/(\d+)/pricing$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_auth();
        $subscription_controller->product_pricing( (int) $matches[1] );
        break;

    /**
     * Subscriptions routes
     */
    case $request_uri === '/subscriptions' && $request_method === 'GET':
        Auth::require_auth();
        $subscription_controller->index();
        break;

    case preg_match( '#^/subscriptions/user/(\d+)$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_auth();
        $subscription_controller->user_subscriptions( (int) $matches[1] );
        break;

    case $request_uri === '/subscriptions/create' && $request_method === 'GET':
        Auth::require_auth();
        $subscription_controller->create_form();
        break;

    case $request_uri === '/subscriptions/create' && $request_method === 'POST':
        Auth::require_auth();
        $subscription_controller->create();
        break;

    case preg_match( '#^/subscriptions/(\d+)/edit$#', $request_uri, $matches ) === 1 && $request_method === 'GET':
        Auth::require_auth();
        $subscription_controller->edit_form( (int) $matches[1] );
        break;

    case preg_match( '#^/subscriptions/(\d+)/update$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_auth();
        $subscription_controller->update( (int) $matches[1] );
        break;

    case preg_match( '#^/subscriptions/(\d+)/cancel$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_auth();
        $subscription_controller->cancel( (int) $matches[1] );
        break;

    case preg_match( '#^/subscriptions/(\d+)/delete$#', $request_uri, $matches ) === 1 && $request_method === 'POST':
        Auth::require_admin();
        $subscription_controller->delete( (int) $matches[1] );
        break;

    /**
     * 404
     */
    default:
        http_response_code( 404 );
        echo '404 - Page not found';
        break;
}
