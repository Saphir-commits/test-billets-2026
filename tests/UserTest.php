<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * UserTest : Tests for User CRUD operations
 *
 * Covers: User::create(), find(), update(), delete(), all()
 * Password hashing and find_by_email are covered in AuthenticationTest.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class UserTest extends TestCase
{
    /**
     * Attributes
     */
    private int $role_id = 0;
    private int $user_id = 0;

    /**
     * setUp() : Create a test role and user before each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->role_id = Role::create( array( 'name' => 'test_user_role' ) );

        $this->user_id = User::create( array(
            'name'     => 'Test User',
            'email'    => 'user_' . uniqid( '', true ) . '@test.local',
            'password' => 'password',
            'role_id'  => $this->role_id,
        ) );
    }

    /**
     * tearDown() : Delete the test user and role after each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function tearDown() : void
    {
        if ( $this->user_id > 0 )
            User::delete( $this->user_id );

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
        $this->assertGreaterThan( 0, $this->user_id );
    }

    /**
     * test_find_returns_correct_data() : find() retrieves name and role_id correctly
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_returns_correct_data() : void
    {
        $user = User::find( $this->user_id );

        $this->assertNotNull( $user );
        $this->assertSame( 'Test User', $user['name'] );
        $this->assertSame( $this->role_id, (int) $user['role_id'] );
    }

    /**
     * test_update_changes_name() : update() changes the user name
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_changes_name() : void
    {
        User::update( $this->user_id, array( 'name' => 'Updated Name' ) );

        $user = User::find( $this->user_id );

        $this->assertSame( 'Updated Name', $user['name'] );
    }

    /**
     * test_update_hashes_new_password() : update() stores the new password as MD5
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_update_hashes_new_password() : void
    {
        /**
         * Variables
         */
        $new_password = 'new_secret_456';

        User::update( $this->user_id, array( 'password' => $new_password ) );

        $user = User::find( $this->user_id );

        $this->assertSame( md5( $new_password ), $user['password'] );
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
        User::delete( $this->user_id );

        $user = User::find( $this->user_id );

        $this->assertNull( $user );

        $this->user_id = 0;
    }

    /**
     * test_all_contains_created_user() : all() includes the newly created user
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_all_contains_created_user() : void
    {
        $users = User::all();
        $ids   = array_map( 'intval', array_column( $users, 'id' ) );

        $this->assertContains( $this->user_id, $ids );
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
        $this->assertNull( User::find( 0 ) );
    }
}
