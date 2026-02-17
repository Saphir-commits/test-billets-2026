<?php

namespace App\Models;

use PDO;
use Carbon\Carbon;

/**
 * Model : Abstract base class for all database models
 *
 * @since 2026
 * @author Samuelle Langlois
 */
abstract class Model
{
    /**
     * Constants
     */
    protected const TABLE = '';
    protected const FILLABLE = array();

    /**
     * all() : Retrieve all records from the table
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return array List of all records
     */
    public static function all() : array
    {
        /**
         * Variables
         */
        $records = array(); // Valeur de retour

        /**
         * Query the database
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->query( 'SELECT * FROM `' . static::TABLE . '` ORDER BY id DESC' );
        $records = $stmt->fetchAll();

        /**
         * Success
         */
        return $records;
    }

    /**
     * find() : Find a single record by its ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id The record ID
     *
     * @return array|null Record data or null if not found
     */
    public static function find( int $id ) : ?array
    {
        /**
         * Variables
         */
        $record = null; // Valeur de retour

        /**
         * Validation of ID
         *
         * SENTINELLE
         */
        if ( $id <= 0 )
            return null;

        /**
         * Query the database
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->prepare( 'SELECT * FROM `' . static::TABLE . '` WHERE id = :id LIMIT 1' );
        $stmt->execute( array( 'id' => $id ) );
        $record = $stmt->fetch();

        if ( $record === false )
            $record = null;

        /**
         * Success
         */
        return $record;
    }

    /**
     * create() : Insert a new record into the table
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param array $data Associative array of column => value
     *
     * @return int The ID of the newly created record
     */
    public static function create( array $data ) : int
    {
        /**
         * Variables
         */
        $id = 0; // Valeur de retour
        $filtered = array();
        $now = Carbon::now()->toDateTimeString();

        /**
         * Filter data to only include fillable columns
         */
        foreach ( static::FILLABLE as $column )
        {
            if ( array_key_exists( $column, $data ) )
                $filtered[$column] = $data[$column];
        }

        $filtered['created_at'] = $now;
        $filtered['edited_at'] = $now;

        /**
         * Build and execute INSERT query
         */
        $columns = implode( ', ', array_map( function ( $col ) { return '`' . $col . '`'; }, array_keys( $filtered ) ) );
        $placeholders = implode( ', ', array_map( function ( $col ) { return ':' . $col; }, array_keys( $filtered ) ) );

        $sql = 'INSERT INTO `' . static::TABLE . '` (' . $columns . ') VALUES (' . $placeholders . ')';

        $pdo = Database::get_instance();
        $stmt = $pdo->prepare( $sql );
        $stmt->execute( $filtered );

        $id = (int) $pdo->lastInsertId();

        /**
         * Success
         */
        return $id;
    }

    /**
     * update() : Update an existing record by its ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int   $id   The record ID
     * @param array $data Associative array of column => value
     *
     * @return bool True if the record was updated
     */
    public static function update( int $id, array $data ) : bool
    {
        /**
         * Variables
         */
        $result = false; // Valeur de retour
        $filtered = array();

        /**
         * Validation of ID
         *
         * SENTINELLE
         */
        if ( $id <= 0 )
            return false;

        /**
         * Filter data to only include fillable columns
         */
        foreach ( static::FILLABLE as $column )
        {
            if ( array_key_exists( $column, $data ) )
                $filtered[$column] = $data[$column];
        }

        $filtered['edited_at'] = Carbon::now()->toDateTimeString();

        /**
         * Build and execute UPDATE query
         */
        $set_clauses = array();
        foreach ( array_keys( $filtered ) as $col )
        {
            $set_clauses[] = '`' . $col . '` = :' . $col;
        }

        $sql = 'UPDATE `' . static::TABLE . '` SET ' . implode( ', ', $set_clauses ) . ' WHERE id = :id';

        $filtered['id'] = $id;

        $pdo = Database::get_instance();
        $stmt = $pdo->prepare( $sql );
        $result = $stmt->execute( $filtered );

        /**
         * Success
         */
        return $result;
    }

    /**
     * delete() : Delete a record by its ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id The record ID
     *
     * @return bool True if the record was deleted
     */
    public static function delete( int $id ) : bool
    {
        /**
         * Variables
         */
        $result = false; // Valeur de retour

        /**
         * Validation of ID
         *
         * SENTINELLE
         */
        if ( $id <= 0 )
            return false;

        /**
         * Execute DELETE query
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->prepare( 'DELETE FROM `' . static::TABLE . '` WHERE id = :id' );
        $result = $stmt->execute( array( 'id' => $id ) );

        /**
         * Success
         */
        return $result;
    }
}
