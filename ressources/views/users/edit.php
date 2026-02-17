<?php $page_title = 'Edit User'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Edit User</h1>

<div class="form-container">
    <form method="POST" action="/users/<?php echo htmlspecialchars( $user['id'] ); ?>/update">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars( $user['name'] ); ?>" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars( $user['email'] ); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current">
        </div>

        <div class="form-group">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id" required>
                <option value="">-- Select a role --</option>
                <?php foreach ( $roles as $role ) : ?>
                    <option value="<?php echo htmlspecialchars( $role['id'] ); ?>" <?php echo ( (int) $role['id'] === (int) $user['role_id'] ) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars( $role['name'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'users' ) ); ?>">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="/users" class="btn btn-primary">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
