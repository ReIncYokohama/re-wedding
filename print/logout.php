<?
session_start();
unset($_SESSION['company_name']);
unset($_SESSION['email']);
unset($_SESSION['printid']);
unset($_SESSION['contact_name']);
//header("Location:index.php");

if (mb_ereg("Firefox", getenv( "HTTP_USER_AGENT" ))) {
	echo "<script type='text/javascript'>";
	echo "alert('ログアウトしました。閉じるボタン等でウインドウを閉じてださい')";
	echo "</script>";
}

echo "<script type='text/javascript'>";
echo " window.open('about:blank','_self').close();";
echo "window.opener = '';";
echo "window.self.close();";
echo "</script>";
return;
?>
