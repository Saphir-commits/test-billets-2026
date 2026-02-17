<?php require __DIR__ . '/../layouts/header.php'; ?>

        <div class="header-actions">
            <h1><?php echo htmlspecialchars( $page_title ); ?></h1>
            <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                <a href="/products-types/create" class="btn btn-primary">Create Product Type</a>
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
                <?php if ( empty( $product_types ) ) : ?>
                    <tr>
                        <td colspan="4">No product types found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ( $product_types as $product_type ) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars( $product_type['id'] ); ?></td>
                            <td><?php echo htmlspecialchars( $product_type['name'] ); ?></td>
                            <td><?php echo htmlspecialchars( $product_type['created_at'] ); ?></td>
                            <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                                <td class="actions">
                                    <a href="/products-types/<?php echo htmlspecialchars( $product_type['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="POST" action="/products-types/<?php echo htmlspecialchars( $product_type['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure you want to delete this product type?' );">
                                        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'products_types' ) ); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
