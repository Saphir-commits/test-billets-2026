<?php

namespace App\Helpers;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

/**
 * Csrf : Helper class for generating and validating CSRF tokens
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Csrf
{
    /**
     * Attributes
     */
    protected static ?CsrfTokenManager $manager = null;

    /**
     * _get_manager() : Return the singleton CsrfTokenManager instance
     *
     * @access protected
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return CsrfTokenManager The CSRF token manager
     */
    protected static function _get_manager() : CsrfTokenManager
    {
        /**
         * Variables
         */
        $manager = null; // Valeur de retour

        /**
         * Lazy instantiation
         */
        if ( static::$manager === null )
            static::$manager = new CsrfTokenManager(
                new UriSafeTokenGenerator(),
                new NativeSessionTokenStorage()
            );

        $manager = static::$manager;

        /**
         * Success
         */
        return $manager;
    }

    /**
     * get_token() : Get the string value of a CSRF token for a given intention
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param string $intention Unique identifier for the form/action
     *
     * @return string The CSRF token value
     */
    public static function get_token( string $intention ) : string
    {
        /**
         * Variables
         */
        $token = ''; // Valeur de retour

        /**
         * Generate or retrieve the token
         */
        $token = static::_get_manager()->getToken( $intention )->getValue();

        /**
         * Success
         */
        return $token;
    }

    /**
     * validate() : Validate a submitted CSRF token against an intention
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param string $intention Unique identifier for the form/action
     * @param string $token     The token value submitted by the form
     *
     * @return bool True if the token is valid
     */
    public static function validate( string $intention, string $token ) : bool
    {
        /**
         * Variables
         */
        $is_valid = false; // Valeur de retour

        /**
         * Validate against the manager
         */
        $is_valid = static::_get_manager()->isTokenValid(
            new CsrfToken( $intention, $token )
        );

        /**
         * Success
         */
        return $is_valid;
    }
}
