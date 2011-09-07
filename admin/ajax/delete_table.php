<?php
	$id = (int)$_GET['id'];
?>
<script>
function deleteGoorNot ()
{
	var id ="<?=$id?>";
	var agree = confirm("会場レイアウトで使用されれています。\n削除してもよろしいですか？");
    if(agree==false) {
        window.location = "../default.php";
    }
    else {
        window.location = "../default.php?action=delete&id="+id;
    }
}
function deleteGo ()
{
	var id ="<?=$id?>";
	window.location = "../default.php?action=delete&id="+id;
}
</script>

<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();

	$nm = $obj->GetRowCount("spssp_default_plan_table"," name=".$id);
	if ($nm>0 || $nm!="") {
			echo '<script> deleteGoorNot(); </script>';
	}
	else {
		echo '<script> deleteGo(); </script>';
	}
?>
