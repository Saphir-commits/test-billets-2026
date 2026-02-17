<?php require __DIR__ . '/layouts/header.php'; ?>

        <h1>Welcome, <?php echo htmlspecialchars( $_SESSION['user_name'] ?? '' ); ?></h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
            <a href="/roles" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Roles</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage user roles</p>
                </div>
            </a>

            <a href="/users" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Users</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage user accounts</p>
                </div>
            </a>

            <a href="/products-types" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Product Types</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage product categories</p>
                </div>
            </a>

            <a href="/pricing-options" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Pricing Options</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage pricing periods</p>
                </div>
            </a>

            <a href="/products" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Products</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage products and pricing</p>
                </div>
            </a>

            <a href="/subscriptions" style="text-decoration: none;">
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">Subscriptions</h3>
                    <p style="color: #777; font-size: 0.9rem;">Manage user subscriptions</p>
                </div>
            </a>
        </div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
