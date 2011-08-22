<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

/*$drag_row_order = $_POST['drop_row_order'];
$drag_column_order = $_POST['drop_column_order'];
$drag_table_visibility = $_POST['drop_table_display'];*/
$drag_table_id = $_POST['drag_table'];


/*$drop_row_order = $_POST['drag_row_order'];
$drop_column_order = $_POST['drag_column_order'];

$drop_table_visibility = $_POST['drag_table_display'];*/

$drop_table_id = $_POST['drop_table'];

$drag_arr['row_order'] = $_POST['drop_row_order'];
$drag_arr['column_order'] = $_POST['drop_column_order'];
$drag_arr['visibility'] = $_POST['drop_table_display'];


$drop_arr['row_order'] = $_POST['drag_row_order'];
$drop_arr['column_order'] = $_POST['drag_column_order'];
$drop_arr['visibility'] = $_POST['drag_table_display'];


$tablename= "spssp_table_layout";


$obj->UpdateData($tablename, $drag_arr, " id=".$drag_table_id);

$obj->UpdateData($tablename, $drop_arr, " id=".$drop_table_id);

	

?>
