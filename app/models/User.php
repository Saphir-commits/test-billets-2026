<?php

namespace App\Models;

/**
 * User : Model for the users table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class User extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'users';
    protected const FILLABLE = array( 'name', 'email', 'password', 'role_id' );

    /**
     * all() : Retrieve all users with their role name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return array List of all users
     */
    public static function all() : array
    {
        /**
         * Variables
         */
        $records = array(); // Valeur de retour

        /**
         * Query the database with JOIN
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->query(
            'SELECT u.*, r.name AS role_name
             FROM `users` u
             LEFT JOIN `roles` r ON u.role_id = r.id
             ORDER BY u.id DESC'
        );
        $records = $stmt->fetchAll();

        /**
         * Success
         */
        return $records;
    }

    /**
     * find_by_email() : Find a user by their email address
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param string $email The email to search for
     *
     * @return array|null User data or null if not found
     */
    public static function find_by_email( string $email ) : ?array
    {
        /**
         * Variables
         */
        $user = null; // Valeur de retour

        /**
         * Validation of email
         *
         * SENTINELLE
         */
        if ( empty( $email ) )
            return null;

        /**
         * Query the database
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->prepare( 'SELECT * FROM users WHERE email = :email LIMIT 1' );
        $stmt->execute( array( 'email' => $email ) );
        $user = $stmt->fetch();

        if ( $user === false )
            $user = null;

        /**
         * Success
         */
        return $user;
    }

    /**
     * create() : Create a new user with hashed password
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param array $data User data
     *
     * @return int The ID of the newly created user
     */
    public static function create( array $data ) : int
    {
        /**
         * Hash password before insert
         */
        if ( isset( $data['password'] ) )
            $data['password'] = md5( $data['password'] );

        /**
         * Success
         */
        return parent::create( $data );
    }

    /**
     * update() : Update an existing user, hash password only if provided
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int   $id   The user ID
     * @param array $data User data
     *
     * @return bool True if the user was updated
     */
    public static function update( int $id, array $data ) : bool
    {
        /**
         * Handle password
         */
        if ( isset( $data['password'] ) && ! empty( $data['password'] ) )
        {
            $data['password'] = md5( $data['password'] );
        }
        else
        {
            unset( $data['password'] );
        }

        /**
         * Success
         */
        return parent::update( $id, $data );
    }

    /**
     * is_current_user() : Check if the given user ID matches the logged-in user
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $id The user ID to check
     *
     * @return bool True if the given ID is the current logged-in user
     */
    public static function is_current_user( int $id ) : bool
    {
        /**
         * Variables
         */
        $is_current = false; // Valeur de retour

        /**
         * Validation of session
         *
         * SENTINELLE
         */
        if ( ! isset( $_SESSION['user_id'] ) )
            return false;

        /**
         * Compare IDs
         */
        if ( (int) $_SESSION['user_id'] === $id )
            $is_current = true;

        /**
         * Success
         */
        return $is_current;
    }
}
