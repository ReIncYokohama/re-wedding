<?php
include_once("inc/dbcon.inc.php");

$db_table_csv = "deccs_concierge_bukken";
$this_name = time();

$fieldset = 'reinid,reg_date,dealtype,disp,rank,gtype,detail,price,tochi,tatemono,balcony,address,address2,line,station,bus,walk,kenpei,youseki,rooms,layout,age,bu_use_region,bu_land_right,bld_name,hand_com,hand_tel,top_floor,floor,description,tanto,sys_date';
$db_query ="select ".$fieldset." from ".$db_table_csv." ORDER BY displayorder DESC";

$query = <<<html
$db_query
html;

$result = mysql_query("$query") or die(mysql_error());

$num = mysql_num_rows($result);
$result_num = "$num";

$lines .= <<<html
"reinid","reg_date","dealtype","disp","rank","gtype","detail","price","tochi","tatemono","balcony","address","address2","line","station","bus","walk","kenpei","youseki","rooms","layout","age","bu_use_region","bu_land_right","bld_name","hand_com","hand_tel","top_floor","floor","description","tanto","sys_date"

html;

$fields_num = mysql_num_fields($result);

while($row = mysql_fetch_row($result)){

	$cl = "";
	$count = count($row);
	for($i=0;$i<32;$i++)
	{
		/*if($i==19)
		{
		    $value = chop($row[$i]);
		    $value1 = chop($row[20]);
			$cl[] = $value .$value2;
			
		    
			$i++;
		}
		else if($i==27)
		{
		    $value = chop($row[$i]);
		    $value1 = chop($row[28]);
			$cl[] = $value .$value1;			
			$i++;
		}
		else
		{ */ $str = array("<p>","</p>","</n>","<br>","<br />");		   
		   $value = str_replace($str,"",chop($row[$i]));
		    $cl[] = "\"$value\"";
		//} 
	}
	
$cl2 = implode(",",$cl);
$cl2 = $cl2."\n";
//$line = mb_convert_encoding("$cl2", "SJIS", "EUC-JP");
$line = mb_convert_encoding("$cl2", "SJIS","UTF8");
$lines .= $line;

}

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=${this_name}.csv");
header("Cache-Control: public");
header("Pragma: public");

echo $lines;
?>
