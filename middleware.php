<?php
class Middleware
{
    public static function handleAuth()
    {
        session_start();

        // Cek apakah user sudah login
        if (!isset($_SESSION['username'])) {
            header("location: login.php");
            exit();
        }
    }

    public static function handleGuest()
    {
        session_start();

        // Redirect ke index jika sudah login
        if (isset($_SESSION['username'])) {
            header("location: index.php");
            exit();
        }
    }

    public static function handleRole($allowed_roles = [])
    {
        session_start();

        // Cek role user
        if (!in_array($_SESSION['level'], $allowed_roles)) {
            header("location: unauthorized.php");
            exit();
        }
    }
}
?>