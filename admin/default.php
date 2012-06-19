<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	include_once("inc/class_information.dbo.php");

	$obj = new DBO();
	$objInfo = new InformationClass();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
	$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

	$_title = $post['default_layout_title'];
	$_view  = $post['rename_table_view'];
	$chng = 0;
	if ($_title != $default_layout_title) $chng = 1;

	if($post['sub']!='' && $chng == 1)
	{
		$sql="Update spssp_options set option_value='".$_title."' where option_name='default_layout_title'";
		mysql_query($sql);
		echo '<script> alert("高砂卓名が更新されました"); </script>';
	}
	$chng = 0;
//echo $post['table_name']." : ".$chng;
	if ($_view != $default_raname_table_view) $chng = 1;
	if($post['table_name']!='' && $chng == 1) {
		$sql="Update spssp_options set option_value='".$_view."' where option_name='rename_table_view'";
		mysql_query($sql);
		if ($post['name']=="") echo '<script> alert("卓名変更が更新されました"); </script>';
	}
	
?>

<?php
	if(isset($post['name']) && $post['name'] != '')
	{

		$id = (int)$post['insert_edit'];
		unset($post['insert_edit']);
		if($id <= 0)
		{
			if(checkTableDuplicasy($post['name']))
			{
				$_post["name "]=$post["name"];
				$_post['display_order'] = time();
				$lid = $obj->InsertData("spssp_tables_name", $_post);
				if($lid > 0)
				{
					//$msg = 1;
					echo '<script> alert("新しい卓名が登録されました"); </script>';
					unset($post);
				}
				else
				{
					$err = 1;
				}
			}
			else
			{
				$err = 19;
			}
		}
		else
		{
			if(checkTableDuplicasy($post['name'] , $id ))
			{
	            $sql = "update spssp_tables_name set name ='".$post['name']."' where id=".$id;
	            mysql_query($sql);
				echo '<script> alert("卓名が変更されました"); </script>';
				//$msg = 4;
			}
			else
			{
				$err = 19;
			}
		}

	}
	if(isset($get['action']) && $get['action']=='sort' && (int)$get['id'] > 0)
	{
		$table = 'spssp_tables_name';

		$id = $get['id'];
		$move = $get['move'];
		if($move=="down") $move="up"; else $move="down";
		$obj->sortItem($table,$id,$move);
		$get['id'] = $get['edit_id'];
	}
	if(isset($get['action']) && $get['action'] !== '' && $get['action'] == "delete")
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$suc = $obj->DeleteRow("spssp_tables_name", "id=".$id);
		}
		else
		{
			$err = 11;
		}
		$get['id'] = $get['edit_id'];
	}

	if(isset($get['action']) && $get['action'] !== '' && $get['action'] == "nodelete")
	{
		$get['id'] = $get['edit_id'];
	}
	if(isset($get['action']) && $get['action']=='edit' && (int)$get['id'] > 0) {
		//$get['name']="";
	}

	//check DEFaULT table NAME DUPLICACY
	function checkTableDuplicasy( $tname, $tableid = false )
	{
		$obj = new DBO();

		if($tableid!="" && $tableid>0)
		{
			$where_check = " id!='".$tableid."' and name='$tname'";
		}
		else
		{
			$where_check = "  name='$tname'";
		}
		$nm = $obj->GetRowCount("spssp_tables_name",$where_check);

		if($nm){return false;}else{return true;}
	}
?>
<?php
	$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
	$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

	if ($get['rename_table_view']!="") $default_raname_table_view = $get['rename_table_view'];
	if ($get['Title']!="") $default_layout_title = $get['Title'];
?>
<script type="text/javascript">
var tablename='';
var Defaultlayouttitle='<?=$default_layout_title?>';
$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});

function isInteger(id){

 var i;
 var s=$("#"+id).val();

	var set =/^([0-9])$/;
	for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);

	    if (!set.test(c))
		{
			var msg=" 半角数字で入力してください";
			$('#'+id).attr("value","");
			 alert(msg);
			 break;
		}
		if(i==0 && c<=0 )
		{
			var msg="0を入力することはできません";
			$('#'+id).attr("value","");
			 alert(msg);
			 break;
		}
    }
    // All characters are numbers.
    return true;
}

function new_table()
{
	$("#insert_edit").val("0");
	$("#title_bar").html("登録　卓名");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{
	$("#name").val("");
}

function check_name(id)
{
	if($("#name").val() == '')
	{
		alert("「卓名」を入力してください");
		$("#name").val(tablename);
		$("#name").focus();
		return false;
	}
	document.new_name.submit();
}
function edit_name(id, name,adminType,boxid)
{
	tablename=name;
	$("#edit_table_name").val(id);
	$("#"+boxid).css({backgroundColor: "#FFF0FF", color: "#990000"});
	if(adminType==222)
	{
		alert("権限がありません");
	}
	else
	{
		$("#insert_edit").val(id);
		$("#name").val(name);
	}
}

function validForm()
{
	document.defaultForm.submit();
}
function validForm_takasago() {
	document.defaultForm_takasago.submit();
}

function clearForm()
{
	window.location="default.php";
}

function confirmDeletePlus(url, id)
{
	var edit_id="<?=$get['id']?>";
	if (typeof(edit_id)=="undefined" || edit_id=="") edit_id = 0;
	var agree = confirm("卓名を削除してもよろしいですか？");
	if(agree)
	{
		if (edit_id != id) window.location = collecting_data(url);
		else               window.location = url;
	}
}

function orderAction(url) {
	window.location = collecting_data(url);
}

function collecting_data(url, edit_id) {
var edit_id="<?=$get['id']?>";
var radio1  = document.defaultForm.rename_table_view[0].checked;
var edit_data;
var urlPlus;
var chng = (radio1==true)? "1": "0";
	edit_data  = "&name="+document.defaultForm.name.value;
	edit_data += "&Title="+document.defaultForm_takasago.default_layout_title.value;
	edit_data += "&rename_table_view="+chng;
	edit_data += "&edit_id="+edit_id;
	urlPlus = url+edit_data;
	return urlPlus;
}

</script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

	<div style="clear:both;"></div>
	<div id="contents">
	<div style="width:100%;"><h4>席次表・席札 &raquo; 卓名</h4></div>

<!-- UCHIDA EDIT 11/08/09 スタッフの場合は表示のみに変更  -->
		<?php
		if ($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222") {
			$InputArea = "";
		}
		else {
			$InputArea = " disabled='disabled'";
		}
		?>

	  <div style="width:100%; border:0px solid #fff" >

	  <form name="defaultForm_takasago" action="default.php" method="post">
	  <input type="hidden" name="sub" value="1" />
	  
<div style="width:100%;"><h2>高砂卓名設定</h2></div>

<table style="width:340px;" border="0" align="left" cellpadding="0" cellspacing="10" >
            <tr>
              <td width="50" align="left" nowrap="nowrap">高砂卓名</td>
                <td width="5" align="left" nowrap="nowrap">：</td>
                <td width="285" nowrap="nowrap">
                	 <?php 				if ($InputArea=="") {?> <input type="text" name="default_layout_title"  id="DefaultLayoutTitle" style="border-style: inset;" value="<?=$default_layout_title?>" /> <?php } else	echo $default_layout_title ?>
           	  </td>
  </tr>
  		</tr>
  		</table>
        <br /><br /><br /><br />
        <?php if ($InputArea=="") { ?>
	  		<table width="100%" border="0" cellspacing="5" cellpadding="0">
	  		<td width="76">&nbsp;</td>
			<td><a href="#"><img onclick="validForm_takasago();"; alt="保存" src="img/common/btn_save.jpg"></a></td>
			</tr>
			</table>
		<?php } ?>
</form>

<br /><br />
	  <form name="defaultForm" action="default.php" method="post">
	  <input type="hidden" name="edit_table_name" id="edit_table_name" value="<?=$get[id]?>" />
	  <input type="hidden" name="insert_edit" value="<?=$get[id]?>"  />
	  <input type="hidden" name="table_name" value="1" />

<div style="width:100%;"><h2> 卓名設定</h2></div>

			<?php if ($InputArea=="") {?>
			<table style="width:680px;" border="0" align="left" cellpadding="0" cellspacing="10">
            <tr align="left">
                <td width="50" align="left" nowrap="nowrap">卓名</td>
                <td width="5" align="left" nowrap="nowrap">：</td>
                <td width="285" nowrap="nowrap"><input type="text" name="name" id="name" style="border-style: inset;"  value="<?=$get['name']?>"/></td>
                <td width="60" nowrap="nowrap">卓名変更</td>
                <td width="10" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	<input type="radio" value="1" name="rename_table_view" <?php 	if($default_raname_table_view == "1"){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;可&nbsp;&nbsp;
					<input type="radio" value="0" name="rename_table_view" <?php 	if($default_raname_table_view == "0" || $default_raname_table_view == ""){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;不可&nbsp;&nbsp;
           	  </td>
	        </tr>
	        </table>
            <?php }
                  else {?>
			<table style="width:340px;" border="0" align="left" cellpadding="0" cellspacing="10">
            <tr align="left">
                <td width="60" nowrap="nowrap">卓名変更</td>
                <td width="10" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	<input type="radio" value="1" name="rename_table_view" <?php 	if($default_raname_table_view == "1"){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;可&nbsp;&nbsp;
					<input type="radio" value="0" name="rename_table_view" <?php 	if($default_raname_table_view == "0" || $default_raname_table_view == ""){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;不可&nbsp;&nbsp;
           	  </td>
           	</tr>
        	</table>
            <?php } ?>

<br /><br /><br /><br />


				<table width="100%" border="0" cellpadding="0" cellspacing="5">
				  <tr>
  <?php 				if ($InputArea=="") { ?>
				    <td width="22" align="left" valign="middle"><p>&nbsp;</p></td>
				    <td width="281" align="left" valign="middle"><a href="#"><img  onclick="validForm();"; border="0" height="22" width="82" alt="登録・更新" src="img/common/btn_regist_update.jpg"></a>　　<a href="#"><img  onclick="clearForm()"; border="0" height="22" width="82" alt="クリア" src="img/common/btn_clear.jpg"></a></td> 
				    <?php } ?>
			      </tr>
				</table>

<br />

	  <?php
//	  }
	  ?>
<p>&nbsp;</p>

        <?php
		if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222")
		{

		?>

		<!-- UCHIDA EDIT 11/07/27 -->
		<!-- <div id="message_BOX" style="height:450px; overflow:auto; width:950px;"> -->
        <div id="box_table" style="width:950px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
                <tr align="center">
                  <td width="10%" nowrap="nowrap">No.</td>
                  <td nowrap="nowrap">卓名</td>
             	  <td nowrap="nowrap">順序変更</td>
                  <td nowrap="nowrap">編集</td>
                  <td nowrap="nowrap">削除</td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order asc ;";
				//echo $query_string;
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;

				foreach($data_rows as $row)
				{
					if($i%2==0)
					{
						$class = 'box5';
					}
					else
					{
						$class = 'box6';
					}
			?>
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
            			<tr align="center">
                        	<td width="10%" nowrap="nowrap"><?=$j?></td>
                         <td nowrap="nowrap"><?=$row['name']?></td>
                             <td nowrap="nowrap">
							  <span class="txt1">
							  <a href="javascript:void(0);" onClick="orderAction('default.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>');">▲</a>
                              <a href="javascript:void(0);" onClick="orderAction('default.php?action=sort&amp;move=down&amp;id=<?=$row['id']?>');">▼</a>
                             </span>
                         </td>
							<td nowrap="nowrap">
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>window.location='default.php?id=<?=$row['id']?>&name=<?=$row['name']?>&userid=<?=$_SESSION['user_type']?>&action=edit';<?php }?>" >
                                	<img src="img/common/btn_edit.gif" width="42" height="17" />
                                </a>
                          </td>
              				<td nowrap="nowrap">
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>confirmDeletePlus('ajax/delete_table.php?id=<?=$row['id']?>', <?=$row['id']?>);<?php }?>">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}

		}else{ ?>
		<!-- UCHIDA EDIT 11/07/27 -->
		<!-- <div id="message_BOX" style="height:450px; overflow:auto; width:950px;"> -->
        <div id="box_table" style="width:950px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
                <tr align="center">
                  <td width="10%" nowrap="nowrap">No.</td>
                  <td nowrap="nowrap">卓名</td>
             	  <td nowrap="nowrap">順序変更</td>
                  <td nowrap="nowrap">編集</td>
                  <td nowrap="nowrap">削除</td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order asc ;";
				//echo $query_string;
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;

				foreach($data_rows as $row)
				{
					if($i%2==0)
					{
						$class = 'box5';
					}
					else
					{
						$class = 'box6';
					}
			?>
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
            			<tr align="center">
                        	<td width="10%" nowrap="nowrap"><?=$j?></td>
                          <td nowrap="nowrap"><?=$row['name']?></td>
                          <td nowrap="nowrap"><font color="gray">▲▼</font></td>
                          <td nowrap="nowrap"> <img src="img/common/btn_edit_greyed.gif" width="42" height="17" /> </td>
                            <td nowrap="nowrap"> <img src="img/common/btn_deleate_greyed.gif" width="42" height="17" /> </td>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}

		}
?>

        <? if($get['id'] !=''){
		?>
		<script>
		$("#boxid<?=$get['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
		</script>
		<? }?>
			</div>
        </div>
</form>
</div>
</div>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
<?php if(isset($err)){
	echo "<script>
		alert('".$obj->GetErrorMsgNew($err)."');
		</script>";
	}?>
<?php if(isset($msg)){	echo "<script>
		alert('".$obj->GetSuccessMsgNew($msg)."');
		</script>"; }
?>
