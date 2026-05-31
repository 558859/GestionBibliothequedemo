<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h($value)
{
    if ($value instanceof DateTimeInterface) {
        $value = $value->format('Y-m-d');
    }

    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirectWithMessage($url, $type, $message, $oldInput = [])
{
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message,
    ];
    $_SESSION['old_input'] = $oldInput;

    header('Location: ' . $url);
    exit;
}

function showMessage()
{
    $flash = $_SESSION['flash_message'] ?? null;

    if (!$flash && !empty($_GET['message'])) {
        $flash = [
            'type' => $_GET['message_type'] ?? 'success',
            'message' => $_GET['message'],
        ];
    }

    if (!$flash) {
        return;
    }

    $type = $flash['type'] ?? 'success';
    $class = 'toast-info-custom';
    $icon = 'bi-info-circle-fill';
    $title = 'Information';

    if ($type === 'success') {
        $class = 'toast-success-custom';
        $icon = 'bi-check-circle-fill';
        $title = 'Succès';
    } elseif ($type === 'error') {
        $class = 'toast-danger-custom';
        $icon = 'bi-x-circle-fill';
        $title = 'Erreur';
    } elseif ($type === 'warning') {
        $class = 'toast-warning-custom';
        $icon = 'bi-exclamation-triangle-fill';
        $title = 'Attention';
    }
    ?>
    <div class="toast-container-custom">
        <div class="toast app-toast <?= h($class) ?>" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
            <div class="toast-body">
                <i class="bi <?= h($icon) ?>"></i>
                <div>
                    <strong><?= h($title) ?></strong>
                    <span><?= h($flash['message'] ?? '') ?></span>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
    <?php
}

function old($key, $default = '')
{
    return $_SESSION['old_input'][$key] ?? $default;
}

function clearFlashData()
{
    unset($_SESSION['flash_message'], $_SESSION['old_input']);
}

function currentDateSql()
{
    // If running on Render (typically SQLite), use SQLite's date('now') function.
    // Render sets the RENDER env var; prefer that detection for portability.
    if (getenv('RENDER')) {
        return "date('now')";
    }

    // Default to SQL Server expression for local environments.
    return 'CAST(GETDATE() AS date)';
}
