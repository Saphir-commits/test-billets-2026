<?php $page_title = 'Edit Product'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Edit Product</h1>

<div class="form-container">
    <form method="POST" action="/products/<?php echo htmlspecialchars( $product['id'] ); ?>/update">
        <div class="form-group">
            <label for="product_type_id">Product Type</label>
            <select id="product_type_id" name="product_type_id" required>
                <option value="">-- Select a type --</option>
                <?php foreach ( $product_types as $type ) : ?>
                    <option value="<?php echo htmlspecialchars( $type['id'] ); ?>" <?php echo ( (int) $type['id'] === (int) $product['product_type_id'] ) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars( $type['name'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="product_pricing_option_id">Pricing Option</label>
            <select id="product_pricing_option_id" name="product_pricing_option_id" required>
                <option value="">-- Select a pricing option --</option>
                <?php foreach ( $pricing_options as $option ) : ?>
                    <option value="<?php echo htmlspecialchars( $option['id'] ); ?>" <?php echo ( (int) $option['id'] === (int) $product['product_pricing_option_id'] ) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars( $option['name'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars( $product['price'] ); ?>" required>
        </div>

        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'products' ) ); ?>">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="/products" class="btn btn-primary">Back</a>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
