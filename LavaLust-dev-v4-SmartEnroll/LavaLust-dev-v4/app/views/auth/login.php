<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'SmartEnroll Login', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>CVHS SmartEnroll</h1>
            <p>Access your account portal.</p>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert success"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('login'); ?>">
                <?= csrf_field(); ?>
                <div>
                    <label for="identifier">Username / Email </label>
                    <input type="text" id="identifier" name="identifier" value="" required>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Sign In</button>
            </form>
            <div class="meta">Role-based access is enforced server-side.</div>
            <p><a href="<?= base_url('register'); ?>">Create a student account</a></p>
        </div>
    </div>
</body>
</html>
