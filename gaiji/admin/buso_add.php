<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include_once("inc/header.inc.php");

$basepath='../../gaiji-image/img_bushu/';
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
		$queryString ="INSERT INTO gaiji_buso (".$field.") values(".$data1.")";

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
				$query_string="update gaiji_buso set buso_image_name='".$samplefile."' where id ='".$newid."' ;";
				mysql_query($query_string);
			}
		}
		
		redirect("buso.php");exit;
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
      <h2><a href="manage.php">TOP</a> &raquo; <a href="buso.php">Buso</a> &raquo; 新規登録</h2>
      
      <div class="txt2">
	  <form name="busoform" id="busoform" action="buso_add.php" method="post" enctype="multipart/form-data">
        <table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
          <tr>
            <td width="25%" align="right">Buso id</td>
            <td width="2%">：</td>
            <td width="73%"><input name="data[buso_id]" type="text" id="buso_id" size="50" /></td>
          </tr>
          <tr>
            <td align="right">Title code<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[title_code]" type="text" id="title_code" size="50" /></td>
          </tr>
          <tr>
            <td align="right">Buso name<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[buso_name]" type="text" id="buso_name" size="20" /></td>
          </tr>
          
         <tr>
            <td align="right">Buso image<span class="txtred">*</span></td>
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
