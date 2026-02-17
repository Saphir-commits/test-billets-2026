<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Subscription : Model for the subscriptions table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Subscription extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'subscriptions';
    protected const FILLABLE = array( 'price', 'user_id', 'product_id', 'expired_at', 'canceled_at' );

    /**
     * will_renew() : Check if the subscription will renew (not canceled)
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return bool True if the subscription will renew
     */
    public static function will_renew( int $subscription_id ) : bool
    {
        /**
         * Variables
         */
        $subscription = self::find( $subscription_id );
        
        /**
         * Success
         */
        return $subscription['canceled_at'] === null;
    }

    /**
     * is_active() : Check if the subscription is active
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return bool True if the subscription is active
     */
    public static function is_active( int $subscription_id ) : bool
    {
        /**
         * Variables
         */
        $subscription = self::find( $subscription_id );
        
        /**
         * Success
         */
        return $subscription['expired_at'] >= Carbon::now();
    }

    /**
     * find_by_user() : Retrieve all subscriptions for a given user
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @param int $user_id The user ID to filter by
     *
     * @return array List of subscriptions for the user
     */
    public static function find_by_user( int $user_id ) : array
    {
        /**
         * Variables
         */
        $records = array(); // Valeur de retour

        /**
         * Query the database with JOINs
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->prepare(
            'SELECT s.*, u.name AS user_name, u.email AS user_email,
                    pt.name AS product_type_name, ppo.name AS pricing_option_name
             FROM `subscriptions` s
             LEFT JOIN `users` u ON s.user_id = u.id
             LEFT JOIN `products` p ON s.product_id = p.id
             LEFT JOIN `products_types` pt ON p.product_type_id = pt.id
             LEFT JOIN `products_pricing_options` ppo ON p.product_pricing_option_id = ppo.id
             WHERE s.user_id = :user_id
             ORDER BY s.id DESC'
        );
        $stmt->execute( array( 'user_id' => $user_id ) );
        $records = $stmt->fetchAll();

        /**
         * Success
         */
        return $records;
    }

    /**
     * all() : Retrieve all subscriptions with user and product info
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return array List of all subscriptions
     */
    public static function all() : array
    {
        /**
         * Variables
         */
        $records = array(); // Valeur de retour

        /**
         * Query the database with JOINs
         */
        $pdo = Database::get_instance();
        $stmt = $pdo->query(
            'SELECT s.*, u.name AS user_name, u.email AS user_email,
                    pt.name AS product_type_name, ppo.name AS pricing_option_name
             FROM `subscriptions` s
             LEFT JOIN `users` u ON s.user_id = u.id
             LEFT JOIN `products` p ON s.product_id = p.id
             LEFT JOIN `products_types` pt ON p.product_type_id = pt.id
             LEFT JOIN `products_pricing_options` ppo ON p.product_pricing_option_id = ppo.id
             ORDER BY s.id DESC'
        );
        $records = $stmt->fetchAll();

        /**
         * Success
         */
        return $records;
    }
}
