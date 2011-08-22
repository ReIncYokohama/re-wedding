<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include_once("inc/header.inc.php");

$basepath='../upload/';
@mkdir($basepath);
$basepath='../upload/gaiji/';
@mkdir($basepath);

	if(isset($_POST['save']))
	{
		$data = $_POST['data'];
		$data[displayorder] = time();
		$data[postdatetime] = date("Y-m-d H:i:s");
		
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{ 
				$fields[] = $key;
				$values[] = "'".jp_encode($value)."'";
			} 
			$field = implode(",",$fields);
			$data1 = implode(",",$values);
		}
		echo $queryString ="INSERT INTO gaiji_option (".$field.") values(".$data1.")";

		mysql_query($queryString);

		$newid=mysql_insert_id();
		if((int)$newid)
		{
			$image= basename($_FILES["image"]["name"]);
			$image_info = @getimagesize($_FILES["image"]["tmp_name"]);
			$image_type = $image_info[2];
			if(!empty($image) && ($image_type==IMAGETYPE_JPEG || $image_type==IMAGETYPE_JPEG2000 || $image_type==IMAGETYPE_JPC || $image_type==IMAGETYPE_JP2 || $image_type==IMAGETYPE_JPX || $image_type==IMAGETYPE_GIF || $image_type==IMAGETYPE_PNG || $image_type==IMAGETYPE_BMP || $image_type==IMAGETYPE_WBMP || $image_type==IMAGETYPE_XBMP))
			{									 									
			
				$samplefile=$newid.".".strtoupper(end(explode(".", $image)));
				
				@move_uploaded_file($_FILES["image"]["tmp_name"],$basepath.$samplefile);
				$query_string="update gaiji_option set g_image_name ='".$samplefile."' where id ='".$newid."' ;";
				mysql_query($query_string);
			}
		}
		
		redirect("gaiji.php");exit;
	}
	
?>
  <div id="topnavi">
    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="">&nbsp;</td>
     
    </tr>
 
</table>
<h1>サンプリンティングシステム 　管理</h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents"> 
      <h2><a href="manage.php">TOP</a> &raquo; <a href="gaiji.php">Buso</a> &raquo; 新規登録</h2>
      
      <div class="txt2">
	  <form name="gaijiform" id="gaijiform" action="gaiji_add.php" method="post" enctype="multipart/form-data">
        <table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
          <tr>
            <td width="25%" align="right">Gaiji name</td>
            <td width="2%">：</td>
            <td width="73%"><input name="data[g_name]" type="text" id="g_name" size="50" /></td>
          </tr>
          <tr>
            <td align="right">Gaiji jis code<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_jis_code]" type="text" id="g_jis_code" size="50" /></td>
          </tr>
          <tr>
            <td align="right">Yomi 1<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi1]" type="text" id="g_yomi1" size="20" /></td>
          </tr>
          <tr>
            <td align="right">Yomi 2<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi2]" type="text" id="g_yomi2" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Yomi 3<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi3]" type="text" id="g_yomi3" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Yomi 4<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi4]" type="text" id="g_yomi4" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Yomi 5<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi5]" type="text" id="g_yomi5" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Yomi 6<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_yomi6]" type="text" id="g_yomi6" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Buso id<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[buso_id]" type="text" id="buso_id" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Gaiji option code<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[option_code]" type="text" id="option_code" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">Detail code<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_detail_code]" type="text" id="g_detail_code" size="50" /></td>
          </tr>
		  <tr>
            <td align="right">CSV No<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[g_csv_no]" type="text" id="g_csv_no" size="50" /></td>
          </tr>
         <tr>
            <td align="right">Gaiji image<span class="txtred">*</span></td>
            <td>：</td>
            <td><input type="file" name="image" /></td>
          </tr>
          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">
			
			<input type="submit" name="save" value="登録"  />
              &nbsp;
            <input type="reset" value="キャンセル"  /></td>
          </tr>
        </table>
		</form>
<p></p>
      </div>
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
