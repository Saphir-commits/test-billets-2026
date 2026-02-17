<?php

namespace Tests;

use App\Helpers\Auth;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * AdminAccessTest : Tests for role-based access control logic
 *
 * Covers: Auth::is_admin(), User::is_current_user().
 * No database needed — tests only manipulate $_SESSION.
 *
 * @since 2026
 * @author Samuelle Langlois
 */
class AdminAccessTest extends TestCase
{
    /**
     * tearDown() : Clear session after each test
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();
    }

    protected function tearDown() : void
    {
        unset( $_SESSION['user_role_id'] );
        unset( $_SESSION['user_id'] );

        parent::tearDown();
    }

    /**
     * test_is_admin_returns_false_without_session() : is_admin() is false when no session is set
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_admin_returns_false_without_session() : void
    {
        unset( $_SESSION['user_role_id'] );

        $this->assertFalse( Auth::is_admin() );
    }

    /**
     * test_is_admin_returns_true_for_admin_role_id() : is_admin() is true when role_id = 1
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_admin_returns_true_for_admin_role_id() : void
    {
        $_SESSION['user_role_id'] = Auth::ADMIN_ROLE_ID;

        $this->assertTrue( Auth::is_admin() );
    }

    /**
     * test_is_admin_returns_false_for_regular_user_role_id() : is_admin() is false for non-admin role
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_admin_returns_false_for_regular_user_role_id() : void
    {
        $_SESSION['user_role_id'] = 2;

        $this->assertFalse( Auth::is_admin() );
    }

    /**
     * test_is_current_user_returns_false_without_session() : is_current_user() is false when not logged in
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_current_user_returns_false_without_session() : void
    {
        unset( $_SESSION['user_id'] );

        $this->assertFalse( User::is_current_user( 1 ) );
    }

    /**
     * test_is_current_user_returns_true_for_matching_id() : is_current_user() is true when IDs match
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_current_user_returns_true_for_matching_id() : void
    {
        $_SESSION['user_id'] = 42;

        $this->assertTrue( User::is_current_user( 42 ) );
    }

    /**
     * test_is_current_user_returns_false_for_different_id() : is_current_user() is false when IDs differ
     *
     * @since 2026
     * @author Samuelle Langlois
     *
     * @return void
     */
    public function test_is_current_user_returns_false_for_different_id() : void
    {
        $_SESSION['user_id'] = 42;

        $this->assertFalse( User::is_current_user( 99 ) );
    }
}
