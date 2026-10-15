<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$user = $user ?? ['full_name' => 'User', 'role' => 'student'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Dashboard', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2>CVHS SmartEnroll</h2>
            <div class="badge"><?= htmlspecialchars(strtoupper((string) ($user['role'] ?? 'user')), ENT_QUOTES, 'UTF-8'); ?></div>
            <nav class="nav">
                <a href="#">Overview</a>
                <a href="#">Profile</a>
                <a href="#">Records</a>
                <a href="#">Announcements</a>
                <a href="#">Notifications</a>
                <a href="#">Settings</a>
                <a href="<?= base_url('logout'); ?>" class="logout">Logout</a>
            </nav>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1 style="margin:0;">Welcome, <?= htmlspecialchars((string) ($user['full_name'] ?? 'User'), ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <div><?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#ecfdf5;color:#065f46;padding:10px 12px;border-radius:8px;margin-bottom:18px;"><?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert" style="background:#fef2f2;color:#991b1b;padding:10px 12px;border-radius:8px;margin-bottom:18px;"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <section class="panel">
                <h3 style="margin-top:0;">Role-based access is active</h3>
                <p>This page is protected by the server-side role check. Only authenticated users with a valid role can access it.</p>

                <div class="grid">
                    <div class="tile">
                        <span>Role</span>
                        <strong><?= htmlspecialchars(strtoupper((string) ($user['role'] ?? 'user')), ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                    <div class="tile">
                        <span>Account</span>
                        <strong>Active</strong>
                    </div>
                    <div class="tile">
                        <span>Status</span>
                        <strong>Secure</strong>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
