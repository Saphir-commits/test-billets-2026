<?php $page_title = 'Roles'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="header-actions">
    <h1>Roles</h1>
    <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
        <a href="/roles/create" class="btn btn-primary">Create Role</a>
    <?php endif; ?>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created At</th>
            <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $roles as $role ) : ?>
            <tr>
                <td><?php echo htmlspecialchars( $role['id'] ); ?></td>
                <td><?php echo htmlspecialchars( $role['name'] ); ?></td>
                <td><?php echo htmlspecialchars( $role['created_at'] ); ?></td>
                <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                    <td>
                        <div class="actions">
                            <a href="/roles/<?php echo htmlspecialchars( $role['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                            
                            <?php if ( \App\Helpers\Auth::is_admin() && $role['id'] != \App\Helpers\Auth::ADMIN_ROLE_ID ) : ?>
                                <form method="POST" action="/roles/<?php echo htmlspecialchars( $role['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure you want to delete this role?' );">
                                    <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'roles' ) ); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
