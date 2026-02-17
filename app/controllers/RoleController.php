<?php

namespace App\Controllers;

use App\Models\Role;
use App\Helpers\Csrf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * RoleController : Handles CRUD operations for roles
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class RoleController
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
     * index() : Display the list of all roles (GET /roles)
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
        $roles = Role::all();

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/roles/index.php';
    }

    /**
     * create_form() : Display the role creation form (GET /roles/create)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function create_form() : void
    {
        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/roles/create.php';
    }

    /**
     * create() : Process role creation form submission (POST /roles/create)
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
        if ( ! Csrf::validate( 'roles', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/roles/create' ) )->send();
            exit();
        }

        /**
         * Validation of name
         *
         * SENTINELLE
         */
        if ( empty( $name ) )
        {
            $_SESSION['flash_error'] = 'Role name is required.';
            ( new RedirectResponse( '/roles/create' ) )->send();
            exit();
        }

        /**
         * Create the role
         */
        Role::create( array( 'name' => $name ) );

        $_SESSION['flash_success'] = 'Role created successfully.';
        ( new RedirectResponse( '/roles' ) )->send();
        exit();
    }

    /**
     * edit_form() : Display the role edit form (GET /roles/{id}/edit)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Role ID from the URL
     *
     * @return void
     */
    public function edit_form( int $id ) : void
    {
        /**
         * Variables
         */
        $role = Role::find( $id );

        /**
         * Validation of role existence
         *
         * SENTINELLE
         */
        if ( $role === null )
        {
            $_SESSION['flash_error'] = 'Role not found.';
            ( new RedirectResponse( '/roles' ) )->send();
            exit();
        }

        /**
         * Render the view
         */
        require __DIR__ . '/../../ressources/views/roles/edit.php';
    }

    /**
     * update() : Process role update form submission (POST /roles/{id}/update)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Role ID from the URL
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
        if ( ! Csrf::validate( 'roles', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/roles/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Validation of name
         *
         * SENTINELLE
         */
        if ( empty( $name ) )
        {
            $_SESSION['flash_error'] = 'Role name is required.';
            ( new RedirectResponse( '/roles/' . $id . '/edit' ) )->send();
            exit();
        }

        /**
         * Update the role
         */
        Role::update( $id, array( 'name' => $name ) );

        $_SESSION['flash_success'] = 'Role updated successfully.';
        ( new RedirectResponse( '/roles' ) )->send();
        exit();
    }

    /**
     * delete() : Delete a role (POST /roles/{id}/delete)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id Role ID from the URL
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
        if ( ! Csrf::validate( 'roles', $csrf_token ) )
        {
            $_SESSION['flash_error'] = 'Invalid security token. Please try again.';
            ( new RedirectResponse( '/roles' ) )->send();
            exit();
        }

        /**
         * Delete the role
         */
        Role::delete( $id );

        $_SESSION['flash_success'] = 'Role deleted successfully.';
        ( new RedirectResponse( '/roles' ) )->send();
        exit();
    }
}
