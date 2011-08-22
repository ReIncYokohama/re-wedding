<?PHP
@session_start();
include_once('admin/inc/ExportToExcel.class.php');

$exp=new ExportToExcel();
$exp->exportWithPage("Yourexcel.html","personalinfo.xls");

//unlink('Yourexcel.html');

?>
