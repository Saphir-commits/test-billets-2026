<?php $page_title = 'Create Role'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Create Role</h1>

<div class="form-container">
    <form method="POST" action="/roles/create">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required autofocus>
        </div>

        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'roles' ) ); ?>">
        <button type="submit" class="btn btn-success">Create</button>
        <a href="/roles" class="btn btn-primary">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
