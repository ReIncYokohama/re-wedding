<?php
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=Report_" . date("Y-m-d").".xls");

//documentation on the spreadsheet package is at:
//http://pear.php.net/manual/en/package.fileformats.spreadsheet-excel-writer.php

include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");



chdir('phpxls');
require_once 'Writer.php';
chdir('..');


 $query_string = "SELECT * from spssp_guest_sub_category";
	//echo $query_string;exit;
	$result = mysql_query( $query_string );
	


$sheet1 =  array(
  array('id','category_id'       ,'name'           ,'display_order'      ,'creation_date'      )
  );
$p=0;
while($row = mysql_fetch_array($result)){
$test[$p]=array(jp_decode($row['id']),jp_decode($row['category_id']),jp_decode($row['name']),jp_decode($row['display_order']),jp_decode($row['creation_date']) );
 
$p++;
}
  
$sheet1 =$sheet1+$test;
//echo "<pre>";
//print_r($sheet1);
//exit;



$workbook = new Spreadsheet_Excel_Writer();

$format_und =& $workbook->addFormat();
$format_und->setBottom(2);//thick
$format_und->setBold();
$format_und->setColor('black');
$format_und->setFontFamily('Arial');
$format_und->setSize(8);

$format_reg =& $workbook->addFormat();
$format_reg->setColor('black');
$format_reg->setFontFamily('MS Gothic');
$format_reg->setSize(10);

$arr = array(
      'C'=>$sheet1      
      );

foreach($arr as $wbname=>$rows)
{
    $rowcount = count($rows);
    $colcount = count($rows[0]);

    $worksheet =& $workbook->addWorksheet($wbname);

    $worksheet->setColumn(0,0, 6.14);//setColumn(startcol,endcol,float)
    $worksheet->setColumn(1,3,15.00);
    $worksheet->setColumn(4,4, 8.00);
    
    for( $j=0; $j<$rowcount; $j++ )
    {
        for($i=0; $i<$colcount;$i++)
        {
            $fmt  =& $format_reg;
            if ($j==0)
                $fmt =& $format_und;

            if (isset($rows[$j][$i]))
            {
                $data=jp_decode($rows[$j][$i]);
                $worksheet->write($j, $i, $data, $fmt);
            }
        }
    }
}

$workbook->send('test.xls');
$workbook->close();

//-----------------------------------------------------------------------------
?>

