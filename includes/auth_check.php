<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Redirect to login if not logged in
function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit();
    }
}

// Redirect to login if not admin
function require_admin()
{
    if (!is_logged_in() || !is_admin()) {
        header('Location: ' . BASE_URL . '/dashboard.php');
        exit();
    }
}

// Redirect if already logged in
function redirect_if_logged_in()
{
    if (is_logged_in()) {
        header('Location: ' . BASE_URL . '/dashboard.php');
        exit();
    }
}
?>