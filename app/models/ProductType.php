<?php

namespace App\Models;

/**
 * ProductType : Model for the products_types table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class ProductType extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'products_types';
    protected const FILLABLE = array( 'name' );
}
