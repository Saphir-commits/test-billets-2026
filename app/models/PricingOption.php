<?php

namespace App\Models;

/**
 * PricingOption : Model for the products_pricing_options table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class PricingOption extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'products_pricing_options';
    protected const FILLABLE = array( 'name', 'nb_days' );
}
