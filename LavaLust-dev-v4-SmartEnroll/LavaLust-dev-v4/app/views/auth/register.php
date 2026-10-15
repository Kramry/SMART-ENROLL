<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$old = $old ?? [];
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Create an Account', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <main class="wrap">
        <section class="card" aria-labelledby="register-title">
            <h1 id="register-title">Create your Account</h1>
            <p>Register for CVHS SmartEnroll.</p>
               <p>Community Vocational High School is the only Technical-Vocational High School in Calapan City.</p>

            <?php if (!empty($errors)): ?>
                <div class="alert error" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('register'); ?>" autocomplete="on">
                <?= csrf_field(); ?>
                <div>
                    <label for="full_name">Full name:</label>
                    <input type="text" id="full_name" name="full_name" maxlength="150" value="<?= htmlspecialchars((string) ($old['full_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="name" required>
                </div>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" minlength="3" maxlength="50" value="<?= htmlspecialchars((string) ($old['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" required>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" minlength="3" maxlength="255" value="<?= htmlspecialchars((string) ($old['address'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="street-address" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" maxlength="255" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="email" required>
                </div>
                <div>
                    <label for="phone">Phone No.:</label>
                    <input type="text" id="phone" name="phone" minlength="10" maxlength="15" value="<?= htmlspecialchars((string) ($old['phone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="tel" required>
                </div>
                <div>
                    <label for="password_confirmation">Confirm password:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="10" maxlength="72" autocomplete="new-password" required>
                </div>
                <button type="submit">Create account</button>
            </form>

            <p><a href="<?= base_url('login'); ?>">Already have an account? Sign in</a></p>
        </section>
    </main>
</body>
</html>