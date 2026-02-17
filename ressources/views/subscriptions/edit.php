<?php require __DIR__ . '/../layouts/header.php'; ?>

        <div class="header-actions">
            <h1><?php echo htmlspecialchars( $page_title ); ?></h1>
            <a href="/subscriptions" class="btn btn-primary">Back to Subscriptions</a>
        </div>

        <div class="form-container">
            <form method="POST" action="/subscriptions/<?php echo htmlspecialchars( $subscription['id'] ); ?>/update">
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select id="user_id" name="user_id" required>
                        <option value="">-- Select a user --</option>
                        <?php foreach ( $users as $user ) : ?>
                            <option value="<?php echo htmlspecialchars( $user['id'] ); ?>"<?php echo ( (int) $user['id'] === (int) $subscription['user_id'] ) ? ' selected' : ''; ?>>
                                <?php echo htmlspecialchars( $user['name'] ); ?> (<?php echo htmlspecialchars( $user['email'] ); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product_id">Product</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">-- Select a product --</option>
                        <?php foreach ( $products as $product ) : ?>
                            <option value="<?php echo htmlspecialchars( $product['id'] ); ?>"<?php echo ( (int) $product['id'] === (int) $subscription['product_id'] ) ? ' selected' : ''; ?>>
                                <?php echo htmlspecialchars( $product['type_name'] ); ?> - <?php echo htmlspecialchars( $product['pricing_option_name'] ); ?> - <?php echo htmlspecialchars( number_format( (float) $product['price'], 2, '.', ' ' ) ); ?> $
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars( $subscription['price'] ); ?>" required>
                </div>

                <div class="form-group">
                    <label for="expired_at">Expired At</label>
                    <input type="datetime-local" id="expired_at" name="expired_at" value="<?php echo htmlspecialchars( str_replace( ' ', 'T', $subscription['expired_at'] ?? '' ) ); ?>" required>
                </div>

                <div class="form-group">
                    <label for="canceled_at">Canceled At (optional)</label>
                    <input type="datetime-local" id="canceled_at" name="canceled_at" value="<?php echo htmlspecialchars( str_replace( ' ', 'T', $subscription['canceled_at'] ?? '' ) ); ?>">
                </div>

                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'subscriptions' ) ); ?>">
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
