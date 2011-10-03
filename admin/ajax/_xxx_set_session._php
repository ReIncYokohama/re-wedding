<?php
@session_start();
$val = $_POST['value'];
$key = $_POST['divitems'];

$_SESSION['cart'][$key] = $val;

if($key == "reset")
{

	unset($_SESSION['cart']);
}
?>
