<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars( $page_title ?? 'Subscription Management' ); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        nav {
            background-color: #2c3e50;
            padding: 0.8rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        nav a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        nav a:hover {
            background-color: #34495e;
        }

        nav .brand {
            font-weight: bold;
            font-size: 1.1rem;
        }

        nav .user-info {
            color: #bdc3c7;
            font-size: 0.85rem;
        }

        .container {
            max-width: 1100px;
            margin: 1.5rem auto;
            padding: 0 1.5rem;
        }

        .flash-success {
            background-color: #d4edda;
            color: #155724;
            padding: 0.7rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }

        .flash-error {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 0.7rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #fecaca;
        }

        h1 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 0.7rem 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #4a90d9;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #357abd;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .actions {
            width: 210px;
            text-align: right;
        }

        .actions form {
            display: inline;
        }

        .form-container {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            color: #555;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4a90d9;
            box-shadow: 0 0 0 2px rgba(74, 144, 217, 0.2);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .header-actions h1 {
            margin-bottom: 0;
        }

        .money {
            text-align: right;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-links">
            <a href="/" class="brand">Subscriptions</a>
            <a href="/roles">Roles</a>
            <a href="/users">Users</a>
            <a href="/products-types">Product Types</a>
            <a href="/pricing-options">Pricing Options</a>
            <a href="/products">Products</a>
            <a href="/subscriptions">Subscriptions</a>
        </div>
        <div class="nav-links">
            <span class="user-info"><?php echo htmlspecialchars( $_SESSION['user_name'] ?? '' ); ?></span>
            <a href="/logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <?php if ( ! empty( $_SESSION['flash_success'] ) ) : ?>
            <div class="flash-success"><?php echo htmlspecialchars( $_SESSION['flash_success'] ); ?></div>
            <?php unset( $_SESSION['flash_success'] ); ?>
        <?php endif; ?>

        <?php if ( ! empty( $_SESSION['flash_error'] ) ) : ?>
            <div class="flash-error"><?php echo htmlspecialchars( $_SESSION['flash_error'] ); ?></div>
            <?php unset( $_SESSION['flash_error'] ); ?>
        <?php endif; ?>
