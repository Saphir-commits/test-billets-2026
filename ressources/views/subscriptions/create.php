<?php require __DIR__ . '/../layouts/header.php'; ?>

        <div class="header-actions">
            <h1><?php echo htmlspecialchars( $page_title ); ?></h1>
            <a href="/subscriptions" class="btn btn-primary">Back to Subscriptions</a>
        </div>

        <div class="form-container">
            <form method="POST" action="/subscriptions/create">
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select id="user_id" name="user_id" required>
                        <option value="">-- Select a user --</option>
                        <?php foreach ( $users as $user ) : ?>
                            <option value="<?php echo htmlspecialchars( $user['id'] ); ?>">
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
                            <option value="<?php echo htmlspecialchars( $product['id'] ); ?>">
                                <?php echo htmlspecialchars( $product['type_name'] ); ?> - <?php echo htmlspecialchars( $product['pricing_option_name'] ); ?> - <?php echo htmlspecialchars( number_format( (float) $product['price'], 2, '.', ' ' ) ); ?> $
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'subscriptions' ) ); ?>">
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
