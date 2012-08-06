<?php
require_once("inc/include_class_files.php");
include_once("inc/checklogin.inc.php");
include_once("inc/new.header.inc.php");
include_once("../fuel/load_classes.php");

$obj = new DBO();

$btn_disp = "";
$ro = "";
if (Core_Session::is_normal_staff()) {
  $btn_disp = " display:none";
  $ro = " readonly='readonly'";
}

$get = $obj->protectXSS($_GET);
$post = $obj->protectXSS($_POST);


	if($post['update']=="update" || $post['insert']=="insert")
	{
		if(trim($post['num_gift_groups']) && trim($post['num_gift_items']))
		{
			$num=$obj->GetNumRows("spssp_gift_criteria"," 1=1");
			if($num==1)
			{
				$spssp_gift_criteria_post[num_gift_items]=$post[num_gift_items];
				$spssp_gift_criteria_post[num_gift_groups]=$post[num_gift_groups];
				$lastid = $obj->UpdateData('spssp_gift_criteria',$spssp_gift_criteria_post,"id=".$post['id']);

					mysql_query("TRUNCATE TABLE `spssp_gift_group_default` ");
					mysql_query("TRUNCATE TABLE `spssp_gift_item_default` ");
					$max_limt_group=$post['num_gift_groups'];
					for($p = 1; $p < $max_limt_group+1; ++$p)
					{
						$arr['name'] = $post['name'.$p];
						$obj->InsertData('spssp_gift_group_default',$arr);
					}
					$max_limt_item=$post['num_gift_items'];
					for($q = 1; $q < $max_limt_item+1; $q++)
					{
						$obj->InsertData('spssp_gift_item_default',array("name"=>GIFT_ITEM_NAME." ".$q));
					}
			}
			else
			{
				//$err=3;
			}
		}
		else
		{
			//$err=2;
		}
		echo "<script> alert('引出物・料理の設定が保存されました'); </script>";
	}

$giftOption = Model_Giftoption::data();

	$menu_criteria_num = $obj->GetNumRows("spssp_menu_criteria"," 1=1");
	if($post['insert2']=="insert" || $post['update2']=="update")
	{
		if(trim($post['num_menu_groups'])<=3)
		{
				mysql_query("TRUNCATE TABLE `spssp_menu_criteria` ");
				unset($arr);
				$arr['num_menu_groups'] = $post['num_menu_groups'];
				$lastid = $obj->InsertData('spssp_menu_criteria',$arr);
		}
		else
		{
			//$err=2;
		}
	}
	if($menu_criteria_num>0)
	{
		$menu_criteria_data_row = $obj->GetAllRow("spssp_menu_criteria");
	}
?>
<script type="text/javascript">
var numgiftitems='<?=$giftOption->num_gift_items?>';
var numgiftgroups='<?=$giftOption->num_gift_groups?>';
var nummenugroups = 3;

function isInteger(id, kind_msg){
 var i;
 var s=$("#"+id).val();
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
       if(i==0&&c==0)
	   {
	   		var msg="0の入力はできません";
			$('#'+id).attr("value","");
			alert(kind_msg+msg);
	   }
	    if (((c < "0") || (c > "9")))
		{
			var msg="半角数字で入力してください";
			$('#'+id).attr("value","");
			 alert(kind_msg+msg);
		}
    }
    // All characters are numbers.
    return true;
}
var reg1 = /^[0-9]$/; // UCHIDA EDIT 11/08/01
function validForm(x)
{
	var num_gift_groups  = document.getElementById('num_gift_groups').value;
	var num_gift_items  = document.getElementById('num_gift_items').value;

	var num_menu_groups  = document.getElementById('num_menu_groups').value;
	r_flg = 0;

	if(!num_gift_items || num_gift_items == 0)
	{
		 alert("引出物商品数を入力してください");
		 document.getElementById('num_gift_items').focus();
		 $('#num_gift_items').val(numgiftitems);
	}
	else
	{
		if(num_gift_items>7)
		{
			 alert("引出物商品数の上限は7種類までです");
			 document.getElementById('num_gift_items').focus();
			 $('#num_gift_items').val(numgiftitems);
			 r_flg = 1;
		}
		if(reg1.test(num_gift_items) == false)
		{
			 if(r_flg == 0)
			 {
			 alert("引出物商品数は半角数字で入力してください");
			 document.getElementById('num_gift_items').focus();
			 }
			 $('#num_gift_items').val(numgiftitems);
			 r_flg = 1;
		}
	}
	if(!num_gift_groups || num_gift_groups == 0)
	{
		 if(r_flg == 0)
		 {
		 alert("引出物グループ数を入力してください");
		 document.getElementById('num_gift_groups').focus();
		 }
		 $('#num_gift_groups').val(numgiftgroups);
		 r_flg = 1;
	}
	else
	{
		if(num_gift_groups>7)
		{
			 if(r_flg == 0)
			 {
			 alert("引出物グループ数の上限は７グループまでです");
			 document.getElementById('num_gift_groups').focus();
			 }
			 $('#num_gift_groups').val(numgiftgroups);
			 r_flg = 1;
		}
		if(reg1.test(num_gift_groups) == false)
		{
			 if(r_flg == 0)
			 {
			 alert("引出物グループ数は半角数字で入力してください");
			 document.getElementById('num_gift_groups').focus();
			 }
			 $('#num_gift_groups').val(numgiftgroups);
			 r_flg = 1;
		}
	}
  for(i=1;i<=7;++i){
    $value = $("#name"+i).val();
    if(num_gift_groups<i && String($value).length>0){
      alert("グループ記号の上限を超えるグループ記号を入力されています");
      r_flg = 1;
      document.getElementById('name'+i).focus();
      break;
    }else if(num_gift_groups>=i && String($value).length == 0){
      alert("ブランクのグループ記号があります");
      r_flg = 1;
      document.getElementById('name'+i).focus();
      break;
    }
    if(String($value).length>2){
      alert("グループ記号は２文字以内で入力してください。");
      r_flg = 1;
      document.getElementById('name'+i).focus();
    }
  }

	if(r_flg == 1)
	{
	return false;
	}
	checkGroupForm(x);
}
function validForm2()
{

	var num_menu_groups  = document.getElementById('num_menu_groups').value;
	if(!num_menu_groups || num_menu_groups == 0)
	{
		 alert("子供料理数を入力してください");
		 document.getElementById('num_menu_groups').focus();
		 return false;
	}
	else
	{
		if(num_menu_groups>3)
		{
			 alert("子供料理数の上限は3種類までです");
			 document.menu_criteria_form.num_menu_groups.value = "<?=$menu_criteria_data_row[0]['num_menu_groups']?>";
			 document.getElementById('num_menu_groups').focus();
			 return false;
		}
		if(reg1.test(num_menu_groups) == false)
		{
			 alert("子供料理数は半角数字で入力してください");
			 document.getElementById('num_menu_groups').focus();
			 $('#num_menu_groups').val(nummenugroups);
			 return false;
		}
	}
	document.gift_criteria_form.submit();
}

var gReg = /[!-~A-Za-z0-9ｦ-ﾝ]$/;
function checkGroupForm(x)
{
	for(var y=1;y<x;y++)
	{
		var gval = $("#name"+y).val();
		if (gval != "") {
			if(gReg.test(gval) == false)
			{
				if (gval.length>1) {
					alert("全角は１文字で入力してください");
					var error =1;
					document.getElementById("name"+y).focus();
					return false;
				}
				else if (gval=="¥" || gval==" " || gval=="  " || gval=="　" || gval=="　　") {
					alert("全角１文字、または半角２文字で入力してください");
					var error =1;
					document.getElementById("name"+y).focus();
					return false;
				}
			}
			else if (gval=="　" || gval=="　　") {
				alert("全角１文字、または半角２文字で入力してください");
				var error =1;
				document.getElementById("name"+y).focus();
				return false;
			}
		}
  }
	validForm2();
}

</script>
<?php include_once("inc/topnavi.php");?>

<div id="container">
<?php if($err){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
<?php if($_GET['msg']){echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?>
<?php if($_SESSION['msg']){echo "<script>
alert('変更されました');
</script>";
unset($_SESSION['msg']);
}?>

<div style="clear:both;"></div>
	<div id="contents">
	 <h4>  <div style="width:300px;">
            	 引出物・料理
</div>
        </h4>
	<div style="width:1035px;"><h2>引出物設定</h2></div>
			<?php
				$group_sql ="SELECT * FROM spssp_gift_group_default  ORDER BY id asc ;";
				$data_rows = $obj->getRowsByQuery($group_sql);

			?>
 		<div style="float:left; width:800px;">
			<form  action="gift.php" method="post" name="gift_criteria_form">
			 <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
			  <tr>
			    <td width="120">引出物商品数</td>
				<td width="10">：</td>
				<td>

          <?php if(!Core_Session::is_normal_staff()) {?>
                <input name="num_gift_items" <?=$ro?> type="text" onlyNumeric="i" id="num_gift_items" size="10" maxlength='1' style="text-align:right;border-style: inset;" value="<?=validation_zero($giftOption->num_gift_items)?>" /> &nbsp;種類（最大7種類まで）
                <?
				}else{
					?>
					<?=validation_zero($giftOption->num_gift_items)?>&nbsp;種類
					<?
					}
				?>
                </td>
			  </tr>
			  <tr>
			    <td>引出物グループ数</td>
				<td>：</td>
				<td>
          <?php if(!Core_Session::is_normal_staff()) {?>
                <input name="num_gift_groups" <?=$ro?> type="text"  onlyNumeric="i" id="num_gift_groups" size="10" maxlength='1' style="text-align:right;border-style: inset;" value="<?=validation_zero($giftOption->num_gift_groups)?>"  />
					&nbsp;グループ（最大7グループまで）
                <?
				}else{
					?>
					<?=validation_zero($giftOption->num_gift_groups)?>&nbsp;グループ
					<?
					}
				?>
        </td>
			  </tr>
			  <tr>
			    <td valign="top">グループ記号</td>
				<td valign="top">：</td>
				<td>
				<?
				$xx = 1;
			foreach($data_rows as $row)
			{
          if(!Core_Session::is_normal_staff()) {
				    echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'><input type='text' style='text-align:right;border-style:inset' id='name".$xx."' ".$ro." name='name".$xx."' size='6' value='".$row['name']."'>";
				}else{
					echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'>".$row['name']."";
				}
				echo "<input type='hidden' style='border-style:inset' name='fieldId".$xx."' value='".$row['id']."'></div>";
				$xx++;
			}
			for (; $xx <=7; $xx++) {
          if(!Core_Session::is_normal_staff()) {
				    echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' size='6' style='border-style:inset' value=''>";
				}else{
					echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'>";
				}
				echo "<input type='hidden' style='border-style:inset' name='fieldId".$xx."' value=''></div>";
			}
		  ?>
				</td>
			  </tr>
			</table>



<div style="clear:both;"></div>
	<br />
<br />
<div style="width:1035px;"><h2>料理設定</h2></div>

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="120">子供料理</td>
    <td width="10">：</td>
    <td>
    <?php if(!Core_Session::is_normal_staff()) {?>
    <input name="num_menu_groups" type="text" id="num_menu_groups" size="10" <?=$ro?> style="text-align:right;border-style: inset;" value="<?=validation_zero($menu_criteria_data_row[0]['num_menu_groups'])?>" />
      種類(最大3種類まで)
      <?
	}else{
        echo validation_zero($menu_criteria_data_row[0]['num_menu_groups'])."&nbsp;種類"; // UCHIDA EDIT 11/08/08 メッセージ変更
		}
	  ?>
      </td>
  </tr>
  <tr>
    <td colspan="3">
	<div style="padding-left:100px; margin-top:3px;">
	<?php if($menu_criteria_num>0)
	{?>

	<input type="hidden" name="update2" value="update" />
	<input type="hidden" name="id" value="<?=$menu_criteria_data_row[0]['id']?>" />
	<?php }
	else
	{?>
	<input type="hidden" name="insert2" value="insert">
	<?php }?>
	</div>

	</td>
  </tr>
</table>
<br /><br />
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="120">&nbsp;</td>
        <td width="10">&nbsp;</td>
	<input type="hidden" name="update" value="update" />
	<input type="hidden" name="id" value="<?=$giftOption->id?>" />
	<a href="#" onclick="validForm(<?=$xx?>);"><img src="img/common/btn_save.jpg" alt="登録" width="82" height="22" style=" <?=$btn_disp?>"  /></a>

	</td>
      </tr>
    </table>
    <br />
<br />
	</form>
	</div>
    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
