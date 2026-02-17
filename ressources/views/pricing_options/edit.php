<?php
/**
 * Pricing Options - Edit View
 *
 * @since 2026
 * @author Samuelle Langlois
 */

$page_title = 'Edit Pricing Option';
require __DIR__ . '/../layouts/header.php';
?>

<div class="header-actions">
    <h1>Edit Pricing Option</h1>
    <a href="/pricing-options" class="btn btn-primary">Back</a>
</div>

<div class="form-container">
    <form method="POST" action="/pricing-options/<?php echo htmlspecialchars( $pricing_option['id'] ); ?>/update">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars( $pricing_option['name'] ); ?>" required>
        </div>

        <div class="form-group">
            <label for="nb_days">Nb Days</label>
            <input type="number" id="nb_days" name="nb_days" min="1" value="<?php echo htmlspecialchars( $pricing_option['nb_days'] ); ?>" required>
        </div>

        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'pricing_options' ) ); ?>">
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
