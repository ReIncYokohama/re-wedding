<?php
	$id = (int)$_GET['id'];
	$name = $_GET['name'];
	$Title = $_GET['Title'];
	$rename_table_view = (int)$_GET['rename_table_view'];
	$edit_id = (int)$_GET['id'];
	$edit_id2 = (int)$_GET['edit_id'];

	$get_msg  = "&id=".$id;
	$get_msg .= "&name=".$name;
	$get_msg .= "&Title=".$Title;
	$get_msg .= "&rename_table_view=".$rename_table_view;
	$get_msg2 = $get_msg."&edit_id=".$edit_id2;
	$get_msg .= "&edit_id=".$edit_id;
?>
<script>
function deleteGoorNot ()
{
	var msg  ="<?=$get_msg?>";
	var msg2 ="<?=$get_msg2?>";
	var agree = confirm("会場レイアウトで使用されれています。\n削除してもよろしいですか？");
    if(agree==false) {
        window.location = "../default.php?action=nodelete"+msg2;
    }
    else {
        window.location = "../default.php?action=delete"+msg2;
    }
}
function deleteGo ()
{
	var msg  ="<?=$get_msg?>";
	var msg2  ="<?=$get_msg2?>";
	window.location = "../default.php?action=delete"+msg2;
}
</script>

<?php
	require_once("../inc/class.dbo.php");
//	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();

	$nm = $obj->GetRowCount("spssp_default_plan_table"," name=".$id);
	if ($nm>0 || $nm!="") {
			echo '<script> deleteGoorNot(); </script>';
	}
	else {
		echo '<script> deleteGo(); </script>';
	}
?>
