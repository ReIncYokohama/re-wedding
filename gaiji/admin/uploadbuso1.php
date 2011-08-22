<?php
    include_once("inc/dbcon.inc.php");
	@session_start();
	include_once("inc/checklogin.inc.php");
include_once("inc/header.inc.php");

    
	$basepath='./tmp/';
	@mkdir($basepath);
	
	$db_table_csv = "gaiji_buso";
	
	

	if(isset($_POST['csvfile']))
	{
		ini_set('upload_max_filesize','20M');
		ini_set('post_max_size','20M');
		ini_set('max_execution_time','1200');
		ini_set('max_input_time','200');
		
		$query_string="SELECT buso_id,id FROM gaiji_buso ORDER BY displayorder DESC ;";
	    $db_result=mysql_query($query_string);	
		while($db_row=mysql_fetch_array($db_result))
		{
			$array_busoid[] = $db_row['buso_id'];
		}
		
		$displayorder = time();
		
		$time = time();
		move_uploaded_file($_FILES['csvfile']['tmp_name'], $basepath.$time.'.csv');
		
		
		$lines = @file($basepath.$time.".csv");

		$line0 = @array_shift($lines);
		//$line0 = str_replace("\"","",$line0);
		$line = explode(",",$line0);
		
		//dumpvar($line);exit;		
		$lnum = count($lines);
		$fieldarray = array('buso_id','title_code','buso_name','buso_image_name');
		
		for($i=0;$i<$lnum;$i++)
		{

			$lines[$i] = str_replace("\"","",$lines[$i]);
			$lines[$i] = chop($lines[$i]);
			list($buso_id,$title_code,$buso_name,$buso_image_name) = explode(",",$lines[$i]);
			$displayorder = $displayorder+$i;  
			
			if(in_array($buso_id,$array_busoid))
			{
				$query='UPDATE gaiji_buso set ';
				for($j=0; $j<count($fieldarray);$j++)
				{
					$string = mb_convert_encoding($$fieldarray[$j], "UTF8", "JIS, eucjp-win, sjis-win");
					$fields = jp_encode1($$fieldarray[$j]);				   
					$query .= $fieldarray[$j] ."='".$string."',";
				}				
				$query .= " where buso_id = '".$$fieldarray[0]."'";
			
			}
			else{
			
				$query='insert into gaiji_buso set ';			
				for($j=0; $j<count($fieldarray);$j++)
				{
					$string = mb_convert_encoding($$fieldarray[$j], "UTF8", "JIS, eucjp-win, sjis-win");
					$query .= $fieldarray[$j] ."='".$string."',";
				}
				$lastupdate = "',postdatetime='".date("Y-m-d H:i:s")."',displayorder='".$displayorder."'";			
				$query .= $lastupdate;
			}
			
			mysql_query($query);			
		}
		
			@unlink($basepath.$time.".csv");
			redirect("buso.php");exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>横浜・川崎の不動産売買は神奈川不動産コンシェルジュ（デックス株式会社）にお任せください</title>
<meta name="description" content="神奈川,横浜,川崎,不動産,住宅情報,(新築・中古一戸建て,土地,マンション)をお届けします。">
<meta name="keywords" content="神奈川,横浜,川崎,不動産,神奈川,横浜市,川崎市,不動産情報,土地,一戸建て,新築,中古物件,マンション,デックス,deccs">
</head>

<body>
<div align="center">
  <p><strong>物件一括登録（CSV） </strong>≫<a href="<?=$sslpath?>admin2/manage.php">TOP</a></p>

  <hr noshade="noshade" />
  <div id="inp_area">
 <form method="post" enctype="multipart/form-data" action="<?=$sslpath?>admin2/uploadbukken.php">
		<table cellpadding="0" cellspacing="5" width="800">
			<? if((int)$_GET['message']){?>
			<tr>
				<td colspan="2" style="text-align:center;">物件の更新が完了しました</td>

			  </tr>
			<? }?>
			<tr>
				<td style="text-align:right;">物件データCSV</td>
				<td><input type="file" name="csvfile" /></td>
			  </tr>
			
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" name="csvfile" value="更新する"/> <input type="button" onClick="javascript:window.location='<?=$sslpath?>admin2/manage.php'" value="キャンセル"/></td>
			</tr>
		</table>
	</form>
<?php
		$query_string="SELECT count(id) AS total_record FROM deccs_concierge_bukken where status=1;";
		$db_result=mysql_query($query_string);
		if($db_row=mysql_fetch_array($db_result))
		{
			$total_record=$db_row['total_record'];
		}

		$total_record  = (int)$total_record;
		$data_per_page = 100;

		$total_page=ceil($total_record/$data_per_page);
		$current_page=(int)$_GET['page'];
		
		if($current_page>$total_page) $current_page=$total_page;
		if($current_page<=0) $current_page=1;

		if($current_page>=$total_page)
			$next=$current_page;
		else
			$next=$current_page+1;
		
		if($current_page<=0)
			$prev=0;
		else
			$prev=$current_page-1;
			
if($_GET['sort'] == '')
{
   $displayorder1 ='displayorder DESC';   
   $_GET['order'] = 'ASC'; 
}
else
{
     $displayorder1 = $_GET['sort'] ." ". $_GET['order'];
	 $_GET['order']= ($_GET['order'] == 'DESC')?"ASC":"DESC";   
}

if($total_page >1)
{
?>
<div >
<?php
	if($prev<$current_page&&$prev>0)
	{
		echo '|  <a href="uploadbukken.php?page='.$prev.'">&lt;&lt;前へ</a>';
	}
	
	if(($prev<$current_page&&$prev>0)||$next>$current_page)
	{
		echo ' | ';
	}
	
	if($next>$current_page)
	{
		echo '<a href="uploadbukken.php?page='.$next.'">次へ&gt;&gt;</a> |';
	}
?>
</div>
<? }?>
 <hr noshade="noshade" />
  <div id="inp_area">
  <script language="javascript">
	 var flag= true;
	function checkallfield()
	{
		var checkboxvalue = document.deleteAllrow.elements['checkfiled'];
		
		if(checkboxvalue.length >= 2)
		{
			if(flag)
			{     	
				for (var i=0; i<checkboxvalue.length; i++)
				{
				  checkboxvalue[i].checked = true;
				}
				flag=false;
			}
			else
			{
				flag=true;
			   for (var i=0; i<checkboxvalue.length; i++)
			   {
				  checkboxvalue[i].checked = false;
				}
			}
		}
		else
		{
			if(flag)
			{ 
				checkboxvalue.checked = true;				
				flag=false;
			}
			else
			{
				flag=true;			   
				checkboxvalue.checked = false;
				
			}
		}
	}

	function deleteAll()
	{
		var checkboxvalue = document.deleteAllrow.elements['checkfiled'];
		var flag = false;
		if(checkboxvalue.length)
		{			
			for (var i=0; i<checkboxvalue.length; i++)
			{
				if (checkboxvalue[i].checked)
				{
				   flag = true;
				}
			}
		}	
		else
		{
			if (checkboxvalue.checked)
			{
				flag = true;				
			}	
		}
		
		if(flag)
		{
		    if(confirm("削除します?"))
			{
		        document.deleteAllrow.submit();
			}
		}
		else
		{
		   alert("Please check one ");
		}
	}	
	</script>
  <form name="deleteAllrow" method="post" action="<?=$sslpath?>admin2/bukken_delete.php?cmd=all&page=<?=(int)$_GET['page']?>">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC" >
      <tbody>
        <tr bgcolor="#FFFFFF">
          <th bgcolor="#E8E8E8" ><input type="button" name="dell_bukken" value="削除" onclick="deleteAll()" /><input type="checkbox" onclick="checkallfield()"/></th>
            
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_event&order=<?=$_GET['order']?>">P公開</a></th>			
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=rank&order=<?=$_GET['order']?>">公開</a></th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=id&order=<?=$_GET['order']?>">ID</a>↓</th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_no&order=<?=$_GET['order']?>">物件番号</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=date_of_registration&order=<?=$_GET['order']?>">登録日</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=dealtype&order=<?=$_GET['order']?>">取引態様</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=disp&order=<?=$_GET['order']?>">表示</a></th>
            
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_type&order=<?=$_GET['order']?>">物件種別</a></th>
           <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=land_area&order=<?=$_GET['order']?>">土地面積</a></th>
		    <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=building_area1&order=<?=$_GET['order']?>">建物面積</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=prices&order=<?=$_GET['order']?>">価格</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=balcony_terrace_area&order=<?=$_GET['order']?>">ﾊﾞﾙｺﾆｰ面積</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=along_the_short1&order=<?=$_GET['order']?>">沿線</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=station_name1&order=<?=$_GET['order']?>">駅</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=name_address1&order=<?=$_GET['order']?>">所在地</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=location_names2&order=<?=$_GET['order']?>">所在地</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=bus_name1&order=<?=$_GET['order']?>">バス</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=walking_minutes1_1&order=<?=$_GET['order']?>">徒歩</a></th>

            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=month_built_year15&order=<?=$_GET['order']?>">築年月</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=take_between_type1&order=<?=$_GET['order']?>">部屋数</a></th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=number_of_rooms_taken_between1&order=<?=$_GET['order']?>">タイプ</a></th>
          
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=building_name&order=<?=$_GET['order']?>">マンション名</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=name_members&order=<?=$_GET['order']?>">担当社名</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=contact_telephone_number1&order=<?=$_GET['order']?>">担当TEL</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=lastupdate&order=<?=$_GET['order']?>">最終更新日</a></th>

          <th width="100px" nowrap="nowrap" bgcolor="#E8E8E8">編集/Preview</th>         
        </tr>
            <?php
            $query_string="SELECT * FROM deccs_concierge_bukken where status='1'  ".$extra." ORDER BY $displayorder1  LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";            
			//echo $query_string;
            $db_result=mysql_query($query_string);
            if(mysql_num_rows($db_result))
            {
				while($db_row=mysql_fetch_array($db_result))
				{
				?>
				
				<tr bgcolor="#FFFFFF" >
				<td style="text-align:center; vertical-align:top;"><input type="checkbox" value="<?=$db_row['id']?>" name="checkfiled[]" id="checkfiled"/></td>
				<td valign="top"  ><input type="checkbox" <?=((int)$db_row['rank'] =='1')?"checked":"";?> id="pickup" name="pickup" onclick="changePickup(<?=$db_row['id']?>);"/></td>
				<td valign="top"  ><input type="checkbox" <?=((int)$db_row['property_event'] =='1')?"checked":"";?> id="member" name="member" onclick="changeMember(<?=$db_row['id']?>);"/></td>
				<td valign="top"  ><?=$db_row['id']?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['property_no'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['date_of_registration'])?></td>
                <td valign="top" style="text-align:left;">
				<?=($db_row['dealtype'] =='1')?"売主":"";?>
				<?=($db_row['dealtype'] =='2')?"代理":"";?>
				<?=($db_row['dealtype'] =='3')?"専属専任媒介":"";?>
				<?=($db_row['dealtype'] =='4')?"専任媒介":"";?>
				<?=($db_row['dealtype'] =='5')?"媒介":"";?>
				
				</td>
                <td valign="top" style="text-align:left;"><?=($db_row['property_event'] =='1')?"公　開":"非公開";?> </td>
                <td valign="top" style="text-align:left;">
				<?=($db_row['property_type'] =='1')?"土　地":"";?>
				<?=($db_row['property_type'] =='2')?"新築一戸建て":"";?>
				<?=($db_row['property_type'] =='3')?"中古一戸建て":"";?>
				<?=($db_row['property_type'] =='4')?"中古マンション":"";?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['land_area'])?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['building_area1'])?></td>
				<td valign="top" style="text-align:left;"><?=number_format(($db_row['prices']/10000),0,'.',',')?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['balcony_terrace_area'])?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['along_the_short1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['station_name1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['name_address1'])?><?=jp_decode($db_row['location_names2'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['location_names3'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['bus_name1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['walking_minutes1_1'])?></td>
                <td valign="top" style="text-align:left;"><?
				
				$str1 = substr($db_row['month_built_year15'],0,-2);
	 $str2 = substr($db_row['month_built_year15'],-2);
	  echo $str1.'年'.$str2.'月'; 
				
				?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['take_between_type1'])?></td>
				 <td valign="top" style="text-align:left;">
						<?=($db_row['number_of_rooms_taken_between1'] =='1')?"R":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='2')?"K":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='3')?"DK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='4')?"LK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='5')?"LDK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='6')?"SK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='7')?"SDK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='8')?"SLK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='9')?"SLDK":""?>
				 
				 </td>
				 
			    <td valign="top" style="text-align:left;"><?=jp_decode($db_row['building_name'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['name_members'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['contact_telephone_number1'])?></td>
                <td valign="top" style="text-align:left;"><?=strftime('%Y-%m-%d',strtotime($db_row['lastupdate']))?></td>
                <td valign="middle" style="text-align:center;"><a href="bukken_edit.php?id=<?=$db_row['id']?>">編集する</a><br><a href="preview.php?id=<?=$db_row['id']?>">Preview</a></td></tr>
             <? } }?>
      
    </table>
    </form>
  </div>
</div>
<br />



</body>
</html>
