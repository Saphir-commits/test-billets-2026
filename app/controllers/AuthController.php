<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AuthController : Handles authentication (login/logout)
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class AuthController
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
     * login_form() : Display the login form (GET /login)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function login_form() : void
    {
        /**
         * Variables
         */
        $error = $_SESSION['login_error'] ?? null;

        /**
         * Clear flash error
         */
        unset( $_SESSION['login_error'] );

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/login.php';
    }

    /**
     * login() : Process login form submission (POST /login)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function login() : void
    {
        /**
         * Variables
         */
        $csrf_token = $this->request->request->get( '_csrf_token', '' );
        $email = trim( $this->request->request->get( 'email', '' ) );
        $password = $this->request->request->get( 'password', '' );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'login', $csrf_token ) )
        {
            $_SESSION['login_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/login' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $email ) || empty( $password ) )
        {
            $_SESSION['login_error'] = 'Email and password are required.';
            ( new RedirectResponse( '/login' ) )->send();
            exit();
        }

        /**
         * Find user and verify password
         */
        $user = User::find_by_email( $email );

        if ( $user === null || $user['password'] !== md5( $password ) )
        {
            $_SESSION['login_error'] = 'Invalid email or password.';
            ( new RedirectResponse( '/login' ) )->send();
            exit();
        }

        /**
         * Create session
         */
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role_id'] = $user['role_id'];

        ( new RedirectResponse( '/' ) )->send();
        exit();
    }

    /**
     * logout() : Destroy session and redirect to login (GET /logout)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function logout() : void
    {
        /**
         * Destroy session
         */
        session_destroy();

        ( new RedirectResponse( '/login' ) )->send();
        exit();
    }
}
