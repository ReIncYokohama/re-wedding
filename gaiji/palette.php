<?php
include('admin/inc/dbcon.inc.php');
include('admin/inc/class.dbo.php');

$obj = new DBO();

$from = $_GET['from'];
	
	if($_POST['search'] == "search")
	{
		$search_item = $_POST['text'];
		$results2 = get_gaizi_char_file($search_item);
		
		//echo "<pre>";
		//print_r($results);
		//exit;	
	}
	if($_POST['get_busho'] == "get_busho")
	{
		
		$search_item = $_POST['search_item'];
		
		$busho_item_value = $_POST['busho_item_value'];
		$busho_item_group = $_POST['busho_item_group'];
		//$results = search_busho_result($search_item);
		if($busho_item_value != ""){
      $results2 = get_busho_sub($busho_item_value,$search_item);
    }
		else {
		$results2 = get_gaizi_char_file($search_item);
		}
		//echo "<pre>";
		//print_r($results2);
		//exit;
		
	}
	if($_POST['get_gaizi_group'] == "get_gaizi_group")
	{		
		$search_item = $_POST['search_item'];
		$busho_item_value = $_POST['busho_item_value'];
		$gr_gaizi_group_code = $_POST['gr_gaizi_group_code'];
		$busho_item_group = $_POST['busho_item_group'];
		//$results = search_busho_result($search_item);
		$results2 = get_gaizi_char_group_all($gr_gaizi_group_code);
		//echo "<pre>";
		//print_r($results2);
		//exit;
		
	}
	$qry = "select * from gaiji_buso order by title_code";
	$results = $obj->getRowsByQuery($qry);
		
	//$qry2 = "select * from spssp_gaizi_char_file";
	//$results2 = $obj->getRowsByQuery($qry2);		
	
	if($_GET['action']=="clear")
	{
		unset($_POST);
	}
	
	
	function get_busho_sub($busho_item_value,$gaizi_item="")
	{
		$obj = new DBO();
		//$qry = "select * from spssp_gaizi_char_file where gr_bushu_code='".$busho_item_value."'";
		//return $results = $obj->getRowsByQuery($qry);
		
		$qry_11 = "select * from spssp_gaizi_char_file where gr_bushu_code='".$busho_item_value."' and (gr_sjis_text_01 like '%".$gaizi_item."%' or  gr_sjis_text_02 like '%".$gaizi_item."%' or  gr_sjis_text_03 like '%".$gaizi_item."%' or  gr_sjis_text_04 like '%".$gaizi_item."%' or  gr_sjis_text_05 like '%".$gaizi_item."%' or  gr_sjis_text_06 like '%".$gaizi_item."%')";
		return $result = $obj->getRowsByQuery($qry_11);
	}
	function get_gaizi_char_file($gaizi_item)
	{
		$obj = new DBO();
		
		$qry_11 = "select * from spssp_gaizi_char_file where gr_sjis_text_01 = '".$gaizi_item."' or  gr_sjis_text_02 ='".$gaizi_item."' or  gr_sjis_text_03 = '".$gaizi_item."' or  gr_sjis_text_04 ='".$gaizi_item."' or  gr_sjis_text_05 ='".$gaizi_item."' or  gr_sjis_text_06 ='".$gaizi_item."'";
		return $result = $obj->getRowsByQuery($qry_11);
	
	}
	function get_gaizi_char_group_all($gr_gaizi_group_code)
	{
		$obj = new DBO();
		$qry = "select * from spssp_gaizi_char_file where gr_gaizi_group_code='".$gr_gaizi_group_code."'";
		return $results = $obj->getRowsByQuery($qry);
	}
	function search_busho_result($search_item)
	{
		$obj = new DBO();
		$qry = "select * from gaiji_buso where 	buso_name like '%".$search_item."%' ";
		return $results = $obj->getRowsByQuery($qry);	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>gaiji_palette</title>
	<link rel="stylesheet" type="text/css" href="css/palette.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/gaiji.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/ans.css" media="all" />
	
	<!--[if IE]>
	<style type="text/css" media="all">.borderitem {border-style: solid;}</style>
	<![endif]-->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script language="javascript" src="js/jquery.rollover.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(function(){ 
         $('a img').hover(function(){ 
            $(this).attr('src', $(this).attr('src').replace('_off', '_on')); 
              }, function(){ 
                 if (!$(this).hasClass('currentPage')) { 
                 $(this).attr('src', $(this).attr('src').replace('_on', '_off')); 
            } 
       }); 
    });
    function check_busho(id)
    {
        $("#busho_item_id").attr("value", id);
		$(".main").css("background","");
		$("#main_"+id).css("background","#EFBFDD");

    }
    function getbusho_form_submit()
    {
        document.get_busho_form.submit();
    }
	/*function getbusho_form_submit()
    {
        $("#search_item").val();
        $("#busho_item_id").val();
		$("#search_item").val();
    }*/
    function select_busho_sub(from,img,sjis_id,gsid,gsid_group)
    {
      //$("#gaiji_busho_id").attr("value",gid );
        $("#gr_uqidx_id").attr("value", gsid);
        $("#gr_sjis_id").attr("value",sjis_id);
        $("#gr_fname_id").attr("value", img);
        $("#gr_gaizi_group_code").attr("value", gsid_group);
        $("#select_sss").attr("src", "../../gaiji-image/img_select/"+img);
    }
    function final_call_parent(from)
    {
        var gr_fname = $("#gr_fname_id").val();
        //var gaiji_busho = $("#gaiji_busho_id").val();
        var gr_uqidx = $("#gr_uqidx_id").val();
        var gaiji_sjis_id = $("#gr_sjis_id").val();
		window.opener.get_gaiji_value(from,gr_fname,gaiji_sjis_id,gr_uqidx);
        window.close();
    }
	function getbusho_group_form_submit()
	{
		 document.get_busho_group_form.submit();
	}
	function write_search_item()
	{
		
		$("#search_item").val($("#text").val());
		
	}
	window.name="modalWin";
    </script>
</head>

<body style="width:500px;height:500px;padding:0px;margin:0px;">
<div id="Div">
</div>
<div id="Div2">
</div>
<div id="Div3">
</div>



<div style="position: relative;">
   <div style="position: absolute; top: 99px; left: 174px; width: 310px; height:300px; overflow:auto;">
<div style="width:310;min-height:303; padding-left:18px;">
<!--ブラウザは非対応です-->
<?php
if(is_array($results2))
{
	echo'<table  border="0" cellspacing="0" cellpadding="0" align="left"><tr>';
	$count='0';
	foreach($results2 as $rows2)
	{ 
		
		$check_br = $rows2['gr_gaizi_group_code'];
		
		if($count>=5)
			{
				echo '</tr><tr>';
				$count=0;
			}
		$count++;
	    echo '<td>';
	?>
	
	
	
	<div id="keepleft">
	

	<div id="main2">
	<img src="img/sber_t.gif" id="sber_t" alt="" />
	<div class="clearFloat"></div>
	<img src="img/sber_l.gif" id="sber_l" alt="" />
	<div id="colwrap2"><a href="#"  onclick="select_busho_sub('<?=$from?>','<?=$rows2['gr_fname']?>',<?=$rows2['gr_chrcode']?>,<?=$rows2['gr_managed_code']?>,<?=$rows2['gr_gaizi_group_code']?>);">
	<img src="../../gaiji-image/img_ans/<?=$rows2['gr_fname']?>" id="sblock2" alt="" /></a>
	<img src="img/sber_u.gif" id="sber_u" alt="" />
	</div>
	<img src="img/sber_r.gif" id="sber_r" alt="" />
	<div class="clearFloat"></div>
	</div></div>
	
	<?php	
	echo'</td>';
	}
	for(;$count<5;$count++)
	{
		echo '<td height="45" align="center" valign="middle">&nbsp;</td>';
	}
	
	echo'</tr></table>';
}
?>
</div>
   </div>
</div>

<div style="position: relative;">
   <div style="position: absolute; top: 99px; left: 14px; width: 160px;">
   
<div style="width:160;height:359px;position:relative; overflow:auto;" id="gaizi_busho_div_id">

<!--ブラウザは非対応です-->
<form action="palette.php?from=<?=$from?>" method="post" name="get_busho_form" target="modalWin">
<input type="hidden" name="get_busho" id="get_busho" value="get_busho">
<input type="hidden" name="search_item" id="search_item" value="<?=$search_item?>">
<input type="hidden"  id="busho_item_id" name="busho_item_value" value="">
</form>
<?php
if(is_array($results))
{
	foreach($results as $rows)
	{ 
		
		if($title_code!=$rows['title_code'])
		{
			$title_code = $rows['title_code'];
			?>
			<img name="bushu_kakusu_01" src="img/bushu_kakusu_<?=$title_code?>.jpg" width="143" height="36" border="0" id="bushu_kakusu_3" alt="" /><br />
		<?php	
		}
	
	?>
		
		<a href="javascript:void(0);" onclick="check_busho(<?=$rows['buso_id']?>);">
		
		<div id="main_<?=$rows['buso_id']?>" class="main">
			<img src="img/bar_t.gif" id="bar_t" alt="" />
				<div class="clearFloat"></div>
			<img src="img/ber_l.gif" id="ber_l" alt="" />
		<div id="colwrap1">
		  <img src="../../gaiji-image/img_bushu/<?=$rows['buso_image_name']?>" id="sblock" alt="" />
						<img src="img/ber_u.gif" id="ber_u" alt="" />
		</div>
			<img src="img/ber_r.gif" id="ber_r" alt="" />
			
		<span class="gaijitext"><?php echo $rows['buso_name'];?></span>
		
				<div class="clearFloat"></div>
		</div>
	
	</a>
	
<?php
	}
}
?>

</div>
</div>
   </div>

<input type="hidden" value="" name="gaiji_busho" id="gaiji_busho_id">
<input type="hidden" value="" name="gr_uqidx" id="gr_uqidx_id">
<input type="hidden" value="" name="gr_fname" id="gr_fname_id">
<input type="hidden" value="" name="gr_sjis" id="gr_sjis_id">


<div id="Div6">
<img src="img/dummiy_select.gif" id="select_sss" alt="選択漢字" />
<form action="palette.php?from=<?=$from?>" method="post" name="get_busho_group_form">
<input type="hidden" name="get_gaizi_group" value="get_gaizi_group">
<input type="hidden" name="search_item" value="<?=$search_item?>">
<input type="hidden"  id="busho_item_id" name="busho_item_value" value="<?=$busho_item_value?>">
<input type="hidden"  id="gr_gaizi_group_code" name="gr_gaizi_group_code" value="">
<input type="hidden"  id="busho_group_id" name="busho_item_group" value="busho_item_group">
<a href="#">
<img src="img/seach2_off.jpg" id="seach2" alt="似た漢字を検索"  onclick="getbusho_group_form_submit();" />
</a>
<!--input type="image" src="img/seach2_off.jpg" /-->
</form>
<a href="palette.php?from=<?=$from?>&action=clear">
<img src="img/clear_off.jpg" id="clear" alt="クリア" /></a>

<a href="javascript:void(0);" onclick="final_call_parent('<?=$from?>');">
<img src="img/ok_off.jpg" id="ok" alt="決定" /></a>

<a href="javascript:void(0);" onclick="window.close();">
<img src="img/close_off.jpg" id="close" alt="閉じる" /></a>

</div>



<div id="Div8">
</div>

<img src="img/guiji_title.jpg" id="guiji_title" alt="外字検索" />
<img src="img/imput.jpg" alt="読み" name="imput" width="160" height="26" id="imput" />

<div style="position: relative;">
<div style="position: absolute; top: 65px; left: 56px; width: 150px;">
	
		<input type="hidden" name="search" value="search">
	<input type="text" id="text" name="text" style="font-size:10pt"; "back ground-color:#ffffff"; onfocus="this.style.backgroundColor='#f9d4e5'" onblur="this.style.backgroundColor='#ffffff'; write_search_item();"  size="13 maxlength="10" value="<?=$search_item?>" />
<script type="text/javascript">
   document.getElementById('text').focus();
</script>

  </div>
</div>

<img src="img/ans_title.jpg" alt="検索結果" width="310" height="36" id="ans_title" />
<img src="img/imput_comment.jpg" id="imput_comment" alt="読みコメント" />
<a href="#">
<img src="img/search_off.jpg" id="search" alt="test" class="rollover" onclick="getbusho_form_submit();"></div>
</a>

</body>
</html>
