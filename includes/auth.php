<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(): void
{
    if (empty($_SESSION['user'])) {
        header('Location: dang-nhap.php');
        exit;
    }
}

function requireRole(string ...$roles): void
{
    requireLogin();

    $role = $_SESSION['user']['role'] ?? '';

    if (!in_array($role, $roles, true)) {
        header('Location: ../index.php');
        exit;
    }
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}