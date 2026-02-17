<?php require __DIR__ . '/../layouts/header.php'; ?>

        <div class="header-actions">
            <h1><?php echo htmlspecialchars( $page_title ); ?></h1>
            <a href="/subscriptions/create" class="btn btn-primary">Create Subscription</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Price ($)</th>
                    <th>Is active</th>
                    <th>Will renew</th>
                    <th>Expired At</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $subscriptions ) ) : ?>
                    <tr>
                        <td colspan="8">No subscriptions found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ( $subscriptions as $subscription ) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars( $subscription['id'] ); ?></td>
                            <td><?php echo htmlspecialchars( $subscription['user_name'] ); ?> (<?php echo htmlspecialchars( $subscription['user_email'] ); ?>)</td>
                            <td><?php echo htmlspecialchars( $subscription['product_type_name'] ); ?> - <?php echo htmlspecialchars( $subscription['pricing_option_name'] ); ?></td>
                            <td><?php echo htmlspecialchars( number_format( (float) $subscription['price'], 2, '.', ' ' ) ); ?>&nbsp;$</td>
                            <td>
                                <?php if ( \App\Models\Subscription::is_active( (int) $subscription['id'] ) ): ?>
                                    Active
                                <?php else: ?>
                                    Inactive
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if ( \App\Models\Subscription::will_renew( (int) $subscription['id'] ) ): ?>
                                    Yes
                                <?php else: ?>
                                    No
                                <?php endif ?>
                            </td>
                            <td><?php echo htmlspecialchars( $subscription['expired_at'] ); ?></td>
                            <td><?php echo htmlspecialchars( $subscription['created_at'] ); ?></td>
                            <td class="actions">
                                <a href="/subscriptions/<?php echo htmlspecialchars( $subscription['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>

                                <?php if ( \App\Models\Subscription::will_renew( (int) $subscription['id'] ) ): ?>
                                    <form method="POST" action="/subscriptions/<?php echo htmlspecialchars( $subscription['id'] ); ?>/cancel" onsubmit="return confirm( 'Are you sure you want to cancel this subscription?' );">
                                        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'subscriptions' ) ); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Cancel</button>
                                    </form>
                                <?php endif ?>
                                
                                <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                                    <form method="POST" action="/subscriptions/<?php echo htmlspecialchars( $subscription['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure you want to delete this subscription?' );">
                                        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'subscriptions' ) ); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
