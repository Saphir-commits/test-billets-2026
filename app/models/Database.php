<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Database : Singleton class for PDO database connection
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Database
{
    /**
     * Attributes
     */
    private static ?PDO $instance = null;

    /**
     * get_instance() : Returns the singleton PDO connection
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return PDO Database connection instance
     */
    public static function get_instance() : PDO
    {
        /**
         * Variables
         */
        $pdo = self::$instance; // Valeur de retour

        /**
         * Validation of existing instance
         *
         * SENTINELLE
         */
        if ( $pdo !== null )
            return $pdo;

        /**
         * Create new connection
         */
        try
        {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME'],
                $_ENV['DB_CHARSET']
            );

            $pdo = new PDO( $dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'] );
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

            self::$instance = $pdo;
        }
        catch ( PDOException $e )
        {
            die( 'Database connection failed: ' . $e->getMessage() );
        }

        /**
         * Success
         */
        return $pdo;
    }
}
