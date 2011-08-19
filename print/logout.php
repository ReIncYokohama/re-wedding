<?
session_start();
unset($_SESSION['company_name']);
unset($_SESSION['email']);
unset($_SESSION['printid']);
unset($_SESSION['contact_name']);
header("Location:index.php");
?>