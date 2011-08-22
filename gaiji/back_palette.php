<?php
include('admin/inc/dbcon.inc.php');
include('admin/inc/class.dbo.php');

$obj = new DBO();

	if($_POST['search'] == "search")
	{
		$search_item = $_POST['text'];
		$results = search_busho_result($search_item);
		/*echo "<pre>";
		print_r($results);
		exit;*/	
	}
	if($_POST['get_busho'] == "get_busho")
	{
		$search_item = $_POST['search_item'];
		$busho_item_value = $_POST['busho_item_value'];
		
		$results = search_busho_result($search_item);
		
		$results2 = get_busho_sub($busho_item_value);
		/*echo "<pre>";
		print_r($results2);
		exit;*/
		
	}
	
	function get_busho_sub($busho_item_value)
	{
		$obj = new DBO();
		$qry = "select * from spssp_gaizi_char_file where gr_bushu_code='".$busho_item_value."'";
		
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
    <style>
    #select_sss
		{
		left: 5px;
		position:absolute;
		top: 5px;
		bottom:5px;
		margin-bottom: 0;
		padding-top: 0px;
		background-color:#FFFFFF;
		}
    </style>
	
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
}
function getbusho_form_submit()
{
	document.get_busho_form.submit();
}
function select_busho_sub(img)
{
	
	$("#select_sss").attr("src", "upload/img_select/"+img);
}
</script>
</head>
<body>
<div id="Div">
</div>
<div id="Div2">
</div>
<div id="Div3">
</div>

<div class="Txt_yomi">
</div>


<div style="position: relative;">
   <div style="position: absolute; top: 99px; left: 174px; width: 310px;">
<div style="width:310;min-height:303;">
<!--ブラウザは非対応です-->
<?php
foreach($results2 as $rows2)
{ ?>
<a href="javascript:void(0);"  onclick="select_busho_sub('<?=$rows2['gr_fname']?>');">
<div id="keepleft">
<div id="main2">
<img src="img/sber_t.gif" id="sber_t" alt="" />
<div class="clearFloat"></div>
<img src="img/sber_l.gif" id="sber_l" alt="" />
<div id="colwrap2">
<img src="upload/img_ans/<?=$rows2['gr_fname']?>" id="sblock2" alt="" />
<img src="img/sber_u.gif" id="sber_u" alt="" />
</div>
<img src="img/sber_r.gif" id="sber_r" alt="" />
<div class="clearFloat"></div>
</div></div>
</a>
<?php
}
?>
</div>
   </div>
</div>

<div style="position: relative;">
   <div style="position: absolute; top: 99px; left: 14px; width: 160px;">
   <img name="bushu_kakusu_01" src="img/bushu_list_title.jpg" width="160" height="36" border="0" id="bushu_kakusu_2" alt="" /><img name="bushu_kakusu_01" src="img/bushu_kakusu_01.jpg" width="160" height="36" border="0" id="bushu_kakusu_3" alt="" /><br />
<div style="width:160;height:300px; overflow:auto;">

<!--ブラウザは非対応です-->
<form action="palette.php" method="post" name="get_busho_form">
<input type="hidden" name="get_busho" value="get_busho">
<input type="hidden" name="search_item" value="<?=$search_item?>">
<input type="hidden"  id="busho_item_id" name="busho_item_value" value="">
</form>
<?php

foreach($results as $rows)
{ ?>
	
	<a href="javascript:void(0);" onclick="check_busho(<?=$rows['buso_id']?>);">
		
		<div id="main">
			<img src="img/bar_t.gif" id="bar_t" alt="" />
				<div class="clearFloat"></div>
			<img src="img/ber_l.gif" id="ber_l" alt="" />
		<div id="colwrap1">
		  <img src="upload/img_bushu/<?=$rows['buso_image_name']?>" id="sblock" alt="" />
						<img src="img/ber_u.gif" id="ber_u" alt="" />
		</div>
			<img src="img/ber_r.gif" id="ber_r" alt="" />
			
		<span class="gaijitext"><?php echo $rows['buso_name'];?></span>
		
				<div class="clearFloat"></div>
		</div>
	
	</a>
	
<?php
}
?>

</div>
</div>
   </div>


<div id="Div6">
<img src="upload/img_ans/F7C7.png" id="select_sss" alt="選択漢字" />

<a href="#">
<img src="img/seach2_off.jpg" id="seach2" alt="似た漢字を検索" />
</a>
<a href="#">
<img src="img/clear_off.jpg" id="clear" alt="クリア" />
</a>
<a href="#">
<img src="img/close_off.jpg" id="close" alt="閉じる" />
</a>
<a href="#">
<img src="img/ok_off.jpg" id="ok" alt="決定" />
</a>

</div>




<div id="Div8">
</div>

<img src="img/guiji_title.jpg" id="guiji_title" alt="外字検索" />
<img src="img/imput.jpg" id="imput" alt="読み" />

<div style="position: relative;">
   <div style="position: absolute; top: 16px; left: 58px; width: 150px;">
<form action="palette.php" method="POST">
	<input type="hidden" name="search" value="search">
	<input type="text" id="text" name="text" size="15" maxlength="10" value="<?=$search_item?>" />
</form>

   </div>
</div>

<img src="img/ans_title.jpg" id="ans_title" alt="検索結果" />
<img src="img/imput_comment.jpg" id="imput_comment" alt="読みコメント" />

<a href="#">
<img src="img/search_off.jpg" id="search" alt="test" class="rollover" onclick="getbusho_form_submit();"></div>
</a>

</body>
</html>
