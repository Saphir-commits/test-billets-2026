<?php

namespace App\Models;

/**
 * Product : Model for the products table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Product extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'products';
    protected const FILLABLE = array( 'product_pricing_option_id', 'product_type_id', 'price' );

    /**
     * all() : Retrieve all products with their type and pricing option names
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return array List of all products
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
            'SELECT p.*, pt.name AS type_name, ppo.name AS pricing_option_name
             FROM `products` p
             LEFT JOIN `products_types` pt ON p.product_type_id = pt.id
             LEFT JOIN `products_pricing_options` ppo ON p.product_pricing_option_id = ppo.id
             ORDER BY p.id DESC'
        );
        $records = $stmt->fetchAll();

        /**
         * Success
         */
        return $records;
    }
}
