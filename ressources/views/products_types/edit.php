<?php require __DIR__ . '/../layouts/header.php'; ?>

        <div class="header-actions">
            <h1><?php echo htmlspecialchars( $page_title ); ?></h1>
            <a href="/products-types" class="btn btn-primary">Back to Product Types</a>
        </div>

        <div class="form-container">
            <form method="POST" action="/products-types/<?php echo htmlspecialchars( $product_type['id'] ); ?>/update">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars( $product_type['name'] ); ?>" required autofocus>
                </div>

                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'products_types' ) ); ?>">
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
