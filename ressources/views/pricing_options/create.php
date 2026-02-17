<?php
/**
 * Pricing Options - Create View
 *
 * @since 2026
 * @author Samuelle Langlois
 */

$page_title = 'Create Pricing Option';
require __DIR__ . '/../layouts/header.php';
?>

<div class="header-actions">
    <h1>Create Pricing Option</h1>
    <a href="/pricing-options" class="btn btn-primary">Back</a>
</div>

<div class="form-container">
    <form method="POST" action="/pricing-options/create">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="nb_days">Nb Days</label>
            <input type="number" id="nb_days" name="nb_days" min="1" required>
        </div>

        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( \App\Helpers\Csrf::get_token( 'pricing_options' ) ); ?>">
        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
