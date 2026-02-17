<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * AuthenticationTest : Tests for authentication logic
 *
 * Covers: User::find_by_email(), password hashing (MD5),
 * and credential validation logic.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class AuthenticationTest extends TestCase
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

        $this->role_id = Role::create( array(
            'name' => 'test_auth_role',
        ) );

        $this->user_id = User::create( array(
            'name'    => 'Auth Test User',
            'email'   => 'auth_' . uniqid( '', true ) . '@test.local',
            'password' => 'secret123',
            'role_id' => $this->role_id,
        ) );
    }

    /**
     * tearDown() : Delete the test role and user after each test
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
     * test_find_by_email_returns_null_for_nonexistent_email() : Unknown email returns null
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_by_email_returns_null_for_nonexistent_email() : void
    {
        $result = User::find_by_email( 'nobody_' . uniqid( '', true ) . '@impossible.local' );

        $this->assertNull( $result );
    }

    /**
     * test_find_by_email_returns_null_for_empty_email() : Empty email triggers sentinelle and returns null
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_by_email_returns_null_for_empty_email() : void
    {
        $result = User::find_by_email( '' );

        $this->assertNull( $result );
    }

    /**
     * test_find_by_email_returns_user_for_valid_email() : Known email returns user array
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_find_by_email_returns_user_for_valid_email() : void
    {
        $test_user = User::find( $this->user_id );
        $result = User::find_by_email( $test_user['email'] );

        $this->assertNotNull( $result );
        $this->assertSame( $this->user_id, (int) $result['id'] );
    }

    /**
     * test_user_password_is_stored_as_md5() : Password is stored as MD5 hash, not plain text
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_user_password_is_stored_as_md5() : void
    {
        /**
         * Variables
         */
        $plain_password = 'secret123';

        $stored_user = User::find( $this->user_id );

        $this->assertNotSame( $plain_password, $stored_user['password'], 'Password should not be stored as plain text' );
        $this->assertSame( md5( $plain_password ), $stored_user['password'], 'Password should be stored as its MD5 hash' );
    }

    /**
     * test_correct_password_validates() : Correct password matches stored hash
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_correct_password_validates() : void
    {
        /**
         * Variables
         */
        $plain_password = 'secret123';

        $stored_user = User::find( $this->user_id );

        $this->assertTrue( md5( $plain_password ) === $stored_user['password'] );
    }

    /**
     * test_wrong_password_fails_validation() : Wrong password does not match stored hash
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_wrong_password_fails_validation() : void
    {
        /**
         * Variables
         */
        $wrong_password = 'wrong_password_xyz';

        $stored_user = User::find( $this->user_id );

        $this->assertFalse( md5( $wrong_password ) === $stored_user['password'] );
    }
}
