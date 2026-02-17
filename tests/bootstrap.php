<?php

/**
 * PHPUnit Bootstrap
 *
 * Loads environment variables from .env and initializes the session
 * array for CLI context before any test runs.
 *
 * @since 2026
 * @author Samuelle Langlois
 */

/**
 * Variables
 */
$env_path = __DIR__ . '/../.env'; // Path to .env file

/**
 * Load environment variables
 */
if ( ! file_exists( $env_path ) )
{
    echo ".env file not found!\n";
    exit( 1 );
}

$env_lines = file( $env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
foreach ( $env_lines as $line )
{
    if ( strpos( trim( $line ), '#' ) === 0 )
        continue;

    list( $name, $value ) = explode( '=', $line, 2 );
    $_ENV[trim( $name )] = trim( $value );
}

/**
 * Initialize session array for CLI context
 * (Auth helpers check $_SESSION without an HTTP session)
 */
$_SESSION = array();

/**
 * Load Composer autoloader
 */
require_once __DIR__ . '/../vendor/autoload.php';
