<?php $page_title = 'Users'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="header-actions">
    <h1>Users</h1>
    <a href="/users/create" class="btn btn-primary">Create User</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $users as $user ) : ?>
            <tr>
                <td><?php echo htmlspecialchars( $user['id'] ); ?></td>
                <td><?php echo htmlspecialchars( $user['name'] ); ?></td>
                <td><?php echo htmlspecialchars( $user['email'] ); ?></td>
                <td><?php echo htmlspecialchars( $user['role_name'] ); ?></td>
                <td><?php echo htmlspecialchars( $user['created_at'] ); ?></td>
                <td>
                    <div class="actions">
                        <a href="/subscriptions/user/<?php echo htmlspecialchars( $user['id'] ); ?>" class="btn btn-primary btn-sm">See active subscription</a>
                        <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                            <a href="/users/<?php echo htmlspecialchars( $user['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" action="/users/<?php echo htmlspecialchars( $user['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure you want to delete this user?' );">
                                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'users' ) ); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
