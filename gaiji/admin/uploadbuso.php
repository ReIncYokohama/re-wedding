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
		$array_busoid = array();
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
		$line = explode(",",$line0);		
		 $lnum = count($lines);

			
		
		for($i=0;$i<$lnum;$i++)
		{

			$lines[$i] = str_replace("\"","",$lines[$i]);
			$lines[$i] = chop($lines[$i]);
			list($buso_id,$title_code,$buso_name,$buso_image_name) = explode(",",$lines[$i]);
			$displayorder = $displayorder+$i;  
			
			if(in_array($buso_id,$array_busoid))
			{
				$query='UPDATE gaiji_buso set buso_id="'.$buso_id.'",title_code="'.$title_code.'",buso_name="'.jp_encode1($buso_name).'",buso_image_name="'.$buso_image_name.'" where where buso_id = "'.$buso_id.'"';
			
			}
			else{
			
				$query="insert into gaiji_buso set buso_id='".$buso_id."',title_code='".$title_code."',buso_name='".jp_encode1($buso_name)."',buso_image_name='".$buso_image_name."'";			
				
				$lastupdate = ", postdatetime='".date("Y-m-d H:i:s")."',displayorder='".$displayorder."'";			
				$query .= $lastupdate;
			}
			//echo $query;
			mysql_query($query);
						
		}
		
			@unlink($basepath.$time.".csv");
			redirect("buso.php");exit;
	}


?>
  <div id="topnavi">
    
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents"> 
      <h2><a href="manage.php">TOP</a> &raquo; ホテル管理</h2>
       
	  
	  
      <div class="box4">
        <form method="post" enctype="multipart/form-data" action="uploadbuso.php">
		<table cellpadding="0" cellspacing="5" width="800">
			<? if((int)$_GET['message']){?>
			<tr>
				<td colspan="2" style="text-align:center;">物件の更新が完了しました</td>

			  </tr>
			<? }?>
			<tr>
				<td style="text-align:right; width:200px;">物件データCSV</td>
				<td><input type="file" name="csvfile" /></td>
			  </tr>
			
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" name="csvfile" value="更新する"/> <input type="button" onClick="javascript:window.location='buso.php'" value="キャンセル"/></td>
			</tr>
		</table>
	</form>
      </div>
	 
      
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
