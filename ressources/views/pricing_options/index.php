<?php
/**
 * Pricing Options - Index View
 *
 * @since 2026
 * @author Samuelle Langlois
 */

$page_title = 'Pricing Options';
require __DIR__ . '/../layouts/header.php';
?>

<div class="header-actions">
    <h1>Pricing Options</h1>
    <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
        <a href="/pricing-options/create" class="btn btn-primary">Create Pricing Option</a>
    <?php endif; ?>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Nb Days</th>
            <th>Created At</th>
            <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $pricing_options as $option ) : ?>
            <tr>
                <td><?php echo htmlspecialchars( $option['id'] ); ?></td>
                <td><?php echo htmlspecialchars( $option['name'] ); ?></td>
                <td><?php echo htmlspecialchars( $option['nb_days'] ); ?></td>
                <td><?php echo htmlspecialchars( $option['created_at'] ); ?></td>
                <?php if ( \App\Helpers\Auth::is_admin() ) : ?>
                    <td>
                        <div class="actions">
                            <a href="/pricing-options/<?php echo htmlspecialchars( $option['id'] ); ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" action="/pricing-options/<?php echo htmlspecialchars( $option['id'] ); ?>/delete" onsubmit="return confirm( 'Are you sure?' );">
                                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'pricing_options' ) ); ?>">
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
