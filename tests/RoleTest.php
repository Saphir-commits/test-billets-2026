<?php

namespace Tests;

use App\Models\Role;
use PHPUnit\Framework\TestCase;

/**
 * RoleTest : Tests for Role CRUD operations
 *
 * Covers: Role::create(), find(), update(), delete(), all()
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class RoleTest extends TestCase
{
    /**
     * Attributes
     */
    private int $role_id = 0;

    /**
     * setUp() : Create a test role before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'test_role_' . uniqid( '', true ) ) );
    }

    /**
     * tearDown() : Delete the test role after each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function tearDown() : void
    {
        if ( $this->role_id > 0 )
            Role::delete( $this->role_id );

        parent::tearDown();
    }

    /**
     * test_create_returns_positive_id() : create() returns a positive integer ID
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_create_returns_positive_id() : void
    {
        $this->assertGreaterThan( 0, $this->role_id );
    }

    /**
     * test_find_returns_correct_name() : find() retrieves the role with the correct name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_name() : void
    {
        $role = Role::find( $this->role_id );

        $this->assertNotNull( $role );
        $this->assertStringStartsWith( 'test_role_', $role['name'] );
    }

    /**
     * test_update_changes_name() : update() changes the role name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_name() : void
    {
        Role::update( $this->role_id, array( 'name' => 'updated_role' ) );

        $role = Role::find( $this->role_id );

        $this->assertSame( 'updated_role', $role['name'] );
    }

    /**
     * test_delete_removes_record() : delete() makes find() return null
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_delete_removes_record() : void
    {
        Role::delete( $this->role_id );

        $role = Role::find( $this->role_id );

        $this->assertNull( $role );

        $this->role_id = 0;
    }

    /**
     * test_all_contains_created_role() : all() includes the newly created role
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_all_contains_created_role() : void
    {
        $roles = Role::all();
        $ids   = array_map( 'intval', array_column( $roles, 'id' ) );

        $this->assertContains( $this->role_id, $ids );
    }

    /**
     * test_find_returns_null_for_invalid_id() : find() returns null for ID <= 0
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_null_for_invalid_id() : void
    {
        $this->assertNull( Role::find( 0 ) );
    }
}
