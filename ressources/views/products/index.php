<?php $page_title = 'Products'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="header-actions">
    <h1>Products</h1>
    <a href="/products/create" class="btn btn-primary">Create Product</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Pricing Option</th>
            <th>Price ($)</th>
            <th>Created At</th>
            <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $products as $product ) : ?>
            <tr>
                <td><?php echo htmlspecialchars( $product['id'] ); ?></td>
                <td><?php echo htmlspecialchars( $product['type_name'] ); ?></td>
                <td><?php echo htmlspecialchars( $product['pricing_option_name'] ); ?></td>
                <td class="money"><?php echo htmlspecialchars( number_format( (float) $product['price'], 2, '.', ' ' ) ); ?>&nbsp;$</td>
                <td><?php echo htmlspecialchars( $product['created_at'] ); ?></td>
                <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                    <td>
                        <div class="actions">
                            <a href="/products/<?php echo htmlspecialchars( $product['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" action="/products/<?php echo htmlspecialchars( $product['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure you want to delete this product?' );">
                                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'products' ) ); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
