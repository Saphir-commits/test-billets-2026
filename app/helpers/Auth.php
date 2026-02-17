<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Auth : Helper class for authentication and authorization checks
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Auth
{
    /**
     * Constants
     */
    public const ADMIN_ROLE_ID = 1;

    /**
     * require_auth() : Redirect to login if not authenticated
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public static function require_auth() : void
    {
        /**
         * Validation of authentication
         *
         * SENTINELLE
         */
        if ( ! isset( $_SESSION['user_id'] ) )
        {
            ( new RedirectResponse( '/login' ) )->send();
            exit();
        }

    }

    /**
     * require_admin() : Redirect to dashboard if the user is not an admin
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public static function require_admin() : void
    {
        /**
         * Ensure user is authenticated first
         */
        self::require_auth();

        /**
         * Validation of admin role
         *
         * SENTINELLE
         */
        if ( ! isset( $_SESSION['user_role_id'] ) || (int) $_SESSION['user_role_id'] !== self::ADMIN_ROLE_ID )
        {
            $_SESSION['flash_error'] = 'Access denied. Admin privileges required.';
            ( new RedirectResponse( '/' ) )->send();
            exit();
        }

    }

    /**
     * is_admin() : Check if the current user is an admin
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return bool True if the user is an admin
     */
    public static function is_admin() : bool
    {
        /**
         * Variables
         */
        $is_admin = false; // Valeur de retour

        /**
         * Check role
         */
        if ( isset( $_SESSION['user_role_id'] ) && (int) $_SESSION['user_role_id'] === self::ADMIN_ROLE_ID )
            $is_admin = true;

        /**
         * Success
         */
        return $is_admin;
    }
}
