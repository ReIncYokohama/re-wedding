<?
session_start();
unset($_SESSION['company_name']);
unset($_SESSION['email']);
unset($_SESSION['printid']);
unset($_SESSION['contact_name']);
//header("Location:index.php");

echo "<script type='text/javascript'>";
echo " window.open('about:blank','_self').close();";
echo "window.opener = '';";
echo "window.self.close();";
echo "</script>";
return;
?>
