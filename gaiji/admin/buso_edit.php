<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include_once("inc/header.inc.php");

$basepath='../../gaiji-image/img_bushu/';
@mkdir($basepath);


	if(isset($_POST['update'])&&(int)$_GET['id'])
	{

		$data = $_POST['data'];
				
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{       
				$string[] = $key."='".jp_encode($value)."'";
			} 
			$fielSets = implode(",",$string);
		}
		$queryString ="UPDATE gaiji_buso set ".$fielSets." where id=".$_GET['id'];

		mysql_query($queryString);

		
		if((int)$_GET['id'])
		{
			if($_POST['oldimage'])			
			{
			   @unlink($basepath.$_POST['oldimage']);
			   $query_string="update gaiji_buso set buso_image_name='' where id ='".$_GET['id']."' ;";
				mysql_query($query_string);
			}
			
			$image= basename($_FILES["image"]["name"]);
			$image_info = @getimagesize($_FILES["image"]["tmp_name"]);
			$image_type = $image_info[2];
			if(!empty($image) && ($image_type==IMAGETYPE_JPEG || $image_type==IMAGETYPE_JPEG2000 || $image_type==IMAGETYPE_JPC || $image_type==IMAGETYPE_JP2 || $image_type==IMAGETYPE_JPX || $image_type==IMAGETYPE_GIF || $image_type==IMAGETYPE_PNG || $image_type==IMAGETYPE_BMP || $image_type==IMAGETYPE_WBMP || $image_type==IMAGETYPE_XBMP))
			{									 									
			
				$samplefile=$_GET['id'].".".strtoupper(end(explode(".", $image)));
				
				@move_uploaded_file($_FILES["image"]["tmp_name"],$basepath.$samplefile);
				$query_string="update gaiji_buso set buso_image_name='".$samplefile."' where id ='".$_GET['id']."' ;";
				mysql_query($query_string);
			}
			
		}
		
		redirect("buso.php?page=".$_GET['page']);exit;
	}
	else
	{

		$query_string="SELECT * FROM gaiji_buso WHERE id='".(int)$_GET['id']."' ORDER BY displayorder DESC LIMIT 0,1;";
		$db_result=mysql_query($query_string);
		$db_row=mysql_fetch_assoc($db_result);
	}

?>
  <div id="topnavi">
    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<h1>サンプリンティングシステム 　管理</h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents"> 
      <h2><a href="manage.php">TOP</a> &raquo; <a href="buso.php">ホテル管理</a> &raquo; 新規登録</h2>
      
      <div class="txt2">
	  <form name="busoform" id="busoform" action="buso_edit.php?id=<?=(int)$_GET['id']?>&page=<?=$_GET['page']?>" method="post" enctype="multipart/form-data">
        <table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
          <tr>
            <td width="25%" align="right">Buso id</td>
            <td width="2%">：</td>
            <td width="73%"><input name="data[buso_id]" value="<?=jp_decode($db_row['buso_id'])?>" type="text" id="buso_id" size="50" /></td>
          </tr>
          <tr>
            <td align="right">Title code<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[title_code]" type="text" id="title_code" size="50" value="<?=jp_decode($db_row['title_code'])?>"/></td>
          </tr>
          <tr>
            <td align="right">Buso name<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="data[buso_name]" type="text" id="buso_name" size="20" value="<?=jp_decode($db_row['buso_name'])?>"/></td>
          </tr>
          
         <tr>
            <td align="right">Buso image<span class="txtred">*</span></td>
            <td>：</td>
            <td>
			<? if($db_row['buso_image_name'] !='') {?>
			<img src="<?=$basepath.$db_row['buso_image_name']?>" />
			<input type="checkbox" name="oldimage" value="<?=$db_row['buso_image_name']?>"/><br>
			<? }?>
			<input type="file" name="image" /></td>
          </tr>
          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">
			
			<input type="submit" name="update" value="登録"  />
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
