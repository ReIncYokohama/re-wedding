<?PHP
@session_start();
include_once('admin/inc/ExportToExcel.class.php');
//include_once('dabasecon.php');//to make database connecti
$exp=new ExportToExcel();
/*-------------un comment it to test with php page---------------*/

$exp->exportWithPage("Yourexcel.html","personalinfo.xls");

//unlink('Yourexcel.html');
