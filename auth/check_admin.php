<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    echo "Access denied";
    exit();
}
?>
