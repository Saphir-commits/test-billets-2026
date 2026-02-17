<?php

namespace App\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Auth;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * UserController : Handles CRUD operations for users
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class UserController
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
     * index() : Display the list of all users (GET /users)
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
        $users = User::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/users/index.php';
    }

    /**
     * create_form() : Display the user creation form (GET /users/create)
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
        $roles = Role::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/users/create.php';
    }

    /**
     * create() : Process user creation form submission (POST /users/create)
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
        $email = trim( $this->request->request->get( 'email', '' ) );
        $password = $this->request->request->get( 'password', '' );
        $role_id = (int) $this->request->request->get( 'role_id', 0 );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'users', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/users/create' ) )->send();
            exit();
        }

        /**
         * Users cannot create admin (Policies)
         *
         * SENTINELLE
         */
        if ( ! Auth::is_admin() && $role_id == Auth::ADMIN_ROLE_ID )
        {
            $_SESSION['flash_error'] = 'You\'ve try to be sneaky!!! YOU DO NOT HAVE THE RIGHT TO create an admin!!!';
            ( new RedirectResponse( '/users/create' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $name ) || empty( $email ) || empty( $password ) || $role_id < 1 )
        {
            $_SESSION['flash_error'] = 'All fields are required.';
            ( new RedirectResponse( '/users/create' ) )->send();
            exit();
        }

        /**
         * Create the user
         */
        User::create( array(
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role_id'  => $role_id
        ) );

        $_SESSION['flash_success'] = 'User created successfully.';
        ( new RedirectResponse( '/users' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the user edit form (GET /users/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id User ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $user = User::find( $id );
        $roles = Role::all();

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
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/users/edit.php';
    }

    /**
     * update() : Process user update form submission (POST /users/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id User ID from the URL
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
        $email = trim( $this->request->request->get( 'email', '' ) );
        $password = $this->request->request->get( 'password', '' );
        $role_id = (int) $this->request->request->get( 'role_id', 0 );

        /**
         * Validation of CSRF token
         *
         * SENTINELLE
         */
        if ( ! Csrf::validate( 'users', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/users/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of required fields
         *
         * SENTINELLE
         */
        if ( empty( $name ) || empty( $email ) || $role_id < 1 )
        {
            $_SESSION['flash_error'] = 'Name, email and role are required.';
            ( new RedirectResponse( '/users/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Update the user
         */
        User::update( $id, array(
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role_id'  => $role_id
        ) );

        $_SESSION['flash_success'] = 'User updated successfully.';
        ( new RedirectResponse( '/users' ) )->send();
        exit();
    }

    /**
     * delete() : Delete a user (POST /users/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id User ID from the URL
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
        if ( ! Csrf::validate( 'users', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/users' ) )->send();
            exit();
        }

        /**
         * Delete the user
         */
        User::delete( $id );

        $_SESSION['flash_success'] = 'User deleted successfully.';
        ( new RedirectResponse( '/users' ) )->send();
        exit();
    }
}
