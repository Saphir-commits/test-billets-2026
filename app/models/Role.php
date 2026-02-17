<?php

namespace App\Models;

/**
 * Role : Model for the roles table
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class Role extends Model
{
    /**
     * Constants
     */
    protected const TABLE = 'roles';
    protected const FILLABLE = array( 'name' );
}
