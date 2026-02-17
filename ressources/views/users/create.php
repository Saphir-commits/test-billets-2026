<?php $page_title = 'Create User'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Create User</h1>

<div class="form-container">
    <form method="POST" action="/users/create">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id" required>
                <option value="">-- Select a role --</option>
                <?php foreach ( $roles as $role ) : ?>
                    <?php 
                    if ( ! \App\Helpers\Auth::is_admin() && $role['id'] == \App\Helpers\Auth::ADMIN_ROLE_ID )
                        continue; 
                    ?>
                    <option value="<?php echo htmlspecialchars( $role['id'] ); ?>">
                        <?php echo htmlspecialchars( $role['name'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'users' ) ); ?>">
        <button type="submit" class="btn btn-success">Create</button>
        <a href="/users" class="btn btn-primary">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
