<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	if($_SESSION['user_type'] == 222)
	{
		$btn_disp = " display:none";
		$ro = " readonly='readonly'";
	}
	else
	{
		$btn_disp = "";
		$ro = "";
	}

	$get = $obj->protectXSS($_GET);

	$gift_criteria_num = $obj->GetNumRows("spssp_gift_criteria"," 1=1");

	if($_POST['insert']=="insert")
	{
		if(trim($_POST['num_gift_groups']) && trim($_POST['num_gift_items']) && trim($_POST['order_deadline']))
		{
			$num=$obj->GetNumRows("spssp_gift_criteria"," 1=1");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['insert']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");

				$spssp_gift_criteria_post[num_gift_items]=$post[num_gift_items];
				$spssp_gift_criteria_post[num_gift_groups]=$post[num_gift_groups];
				$spssp_gift_criteria_post[order_deadline]=$post[order_deadline];

				$lastid = $obj->InsertData('spssp_gift_criteria',$spssp_gift_criteria_post);

				if($lastid)
				{
					mysql_query("TRUNCATE TABLE `spssp_gift_group_default` ");
					mysql_query("TRUNCATE TABLE `spssp_gift_item_default` ");
					$max_limt_group=65+$post['num_gift_groups'];
					for($p = '65'; $p < $max_limt_group; ++$p)
					{
						$obj->InsertData('spssp_gift_group_default',array("name" =>chr($p)));
					}
					$max_limt_item=1+$post['num_gift_items'];
					for($q = 1; $q < $max_limt_item; $q++)
					{
						$obj->InsertData('spssp_gift_item_default',array("name"=>"'".GIFT_ITEM_NAME." ".$q."'"));
					}
//					$msg=1;
//					redirect("gift.php?msg=".$msg);

				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;
		}
	}

	if($_POST['update']=="update")
	{
		if(trim($_POST['num_gift_groups'])<=7 && trim($_POST['num_gift_items'])<=7 && trim($_POST['order_deadline']))
		{
						$num=$obj->GetNumRows("spssp_gift_criteria"," 1=1");
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");

				$spssp_gift_criteria_post[num_gift_items]=$post[num_gift_items];
				$spssp_gift_criteria_post[num_gift_groups]=$post[num_gift_groups];
				$spssp_gift_criteria_post[order_deadline]=$post[order_deadline];
				$lastid = $obj->UpdateData('spssp_gift_criteria',$spssp_gift_criteria_post,"id=".$_POST['id']);

					mysql_query("TRUNCATE TABLE `spssp_gift_group_default` ");
					mysql_query("TRUNCATE TABLE `spssp_gift_item_default` ");
					$max_limt_group=65+$post['num_gift_groups'];
					for($p = '65'; $p < $max_limt_group; ++$p)
					{
						$obj->InsertData('spssp_gift_group_default',array("name" =>chr($p)));
					}
					$max_limt_item=1+$post['num_gift_items'];
					for($q = 1; $q < $max_limt_item; $q++)
					{
						$obj->InsertData('spssp_gift_item_default',array("name"=>GIFT_ITEM_NAME." ".$q));
					}
//					$_SESSION['msg']=2;
					//redirect("gift.php?msg=".$msg);


			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;
		}
	}
	if($gift_criteria_num>0)
	{
		$gift_criteria_data_row = $obj->GetAllRow("spssp_gift_criteria");
	}

	$menu_criteria_num = $obj->GetNumRows("spssp_menu_criteria"," 1=1");

	if($_POST['insert2']=="insert")
	{
		if(trim($_POST['num_menu_groups'])<=3)
		{
			$num=$obj->GetNumRows("spssp_menu_criteria"," 1=1");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['insert2']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");

				$lastid = $obj->InsertData('spssp_menu_criteria',$post);

				if($lastid)
				{
//					$msg=1;
//					redirect("gift.php?msg=".$msg);

				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;
		}
	}
	if($_POST['update2']=="update")
	{
		if(trim($_POST['num_menu_groups'])<=3)
		{
			$num=$obj->GetNumRows("spssp_menu_criteria"," 1=1");
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update2']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");

				$lastid = $obj->UpdateData('spssp_menu_criteria',$post,"id=".$_POST['id']);

				if($lastid)
				{
//					$msg=2;
//					redirect("gift.php?msg=".$msg);

				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;
		}
	}
	if($menu_criteria_num>0)
	{
		$menu_criteria_data_row = $obj->GetAllRow("spssp_menu_criteria");
	}
	//DEFAULT GROUP NAME UPDaTE STart

	if($_POST['editUserGiftGroupsUpdate']=='editUserGiftGroupsUpdate')
	{
		unset($_POST['editUserGiftGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift_group_default", $array," id=".(int)$_POST['fieldId'.$i]);
		}

	}
	//DEFAULT GROUP NAME UPDaTE END
?>
<script type="text/javascript">
var numgiftitems='<?=$gift_criteria_data_row[0]['num_gift_items']?>';
var numgiftgroups='<?=$gift_criteria_data_row[0]['num_gift_groups']?>';
var orderdeadline='<?=$gift_criteria_data_row[0]['order_deadline']?>';
var nummenugroups = 3;
$(document).ready(function(){

	$('#num_gift_items').keyup(function(){
		var r=isInteger("num_gift_items","引出物商品数は");
    });
    $('#num_gift_groups').keyup(function(){
		var r=isInteger("num_gift_groups","引出物グループ数");
    });
	$('#order_deadline').keyup(function(){
		var r=isInteger("order_deadline","発注締切日は");
    });
	$('#num_menu_groups').keyup(function(){
		var r=isInteger("num_menu_groups","子供料理数は");
    });
});

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
	var order_deadline  = document.getElementById('order_deadline').value;
	var num_menu_groups  = document.getElementById('num_menu_groups').value;
	$r_flg = 0;

// UCHIDA EDIT 11/08/01 入力チェック追加
// AKIKUSA EDIT 11/08/19 2種類以上のエラーの場合の処理を追加
	if(!num_gift_items || num_gift_items == 0)
	{
		 alert("引出物商品数を入力してください");
		 document.getElementById('num_gift_items').focus();
		 $('#num_gift_items').val(numgiftitems);
//		 return false;
	}
	else
	{
		if(num_gift_items>7)
		{
			 alert("引出物商品数の上限は7種類までです");
			 document.getElementById('num_gift_items').focus();
			 $('#num_gift_items').val(numgiftitems);
			 $r_flg = 1;
//			 return false;
		}
		if(reg1.test(num_gift_items) == false)
		{
			 if($r_flg == 0)
			 {
			 alert("引出物商品数は半角数字で入力してください");
			 document.getElementById('num_gift_items').focus();
			 }
			 $('#num_gift_items').val(numgiftitems);
			 $r_flg = 1;
//			 return false;
		}
	}

	if(!num_gift_groups || num_gift_groups == 0)
	{
		 if($r_flg == 0)
		 {
		 alert("引出物グループ数を入力してください");
		 document.getElementById('num_gift_groups').focus();
		 }
		 $('#num_gift_groups').val(numgiftgroups);
		 $r_flg = 1;
//		 return false;
	}
	else
	{
		if(num_gift_groups>7)
		{
			 if($r_flg == 0)
			 {
			 alert("引出物グループ数の上限は７グループまでです");
			 document.getElementById('num_gift_groups').focus();
			 }
			 $('#num_gift_groups').val(numgiftgroups);
			 $r_flg = 1;
//			 return false;
		}
		if(reg1.test(num_gift_groups) == false)
		{
			 if($r_flg == 0)
			 {
			 alert("引出物グループ数は半角数字で入力してください");
			 document.getElementById('num_gift_groups').focus();
			 }
			 $('#num_gift_groups').val(numgiftgroups);
			 $r_flg = 1;
//			 return false;
		}
	}
	if(!order_deadline || order_deadline == 0)
	{
		 if($r_flg == 0)
		 {
		 alert("発注締切日を入力してください");
		 document.getElementById('order_deadline').focus();
		 }
		 $('#order_deadline').val(orderdeadline);
		 $r_flg = 1;
//		 return false;
	}
	if(reg1.test(order_deadline) == false)
	{
		 if($r_flg == 0)
		 {
		 alert("発注締切日は半角数字で入力してください");
		 document.getElementById('order_deadline').focus();
		 }
		 $('#order_deadline').val(orderdeadline);
		 $r_flg = 1;
//		 return false;
	}
	if($r_flg == 1)
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
	document.menu_criteria_form.submit();
}
// UCHIDA EDIT 11/08/09 半角英数字１文字のみ有効に設定
var gReg = /^[A-Za-z0-9]$/;
function checkGroupForm(x)
{	//alert(x);

	for(var y=1;y<x;y++)
	{
		var gval = $("#name"+y).val();
		if(gReg.test(gval)==false && gval != "")
		{
			alert("半角英数字１文字で入力してください");
			var error =1;
			document.getElementById("name"+y).focus();
			return false;
		}
	}
	if(error!=1)
	{
		document.gift_criteria_form.submit();
	}
}
</script>
<div id="topnavi">
<?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
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
	 <h2>  <div style="width:300px;">
            	 引出物・料理
</div>
        </h2>
	<div style="width:1035px;"><h2>引出物設定</h2></div>
	<div>
			<?php
				$group_sql ="SELECT * FROM spssp_gift_group_default  ORDER BY id asc ;";
				$data_rows = $obj->getRowsByQuery($group_sql);

			?>
 <div style="float:left; width:800px;">
			<form  action="gift.php" method="post" name="gift_criteria_form">
			 <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
			  <tr>
				<td width="100">引出物商品数　　：</td>
				<td>

                <?php if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){?>
                <input name="num_gift_items" <?=$ro?> type="text" onlyNumeric="i" id="num_gift_items" size="10" maxlength='1' value="<?=$gift_criteria_data_row[0]['num_gift_items']?>" /> &nbsp;種類（最大7種類まで）
                <?
				}else{
					?>
					<?=$gift_criteria_data_row[0]['num_gift_items']?>&nbsp;種類 <!-- UCHIDA EDIT 11/08/08 メッセージ変更  -->
					<?
					}
				?>
                </td>
			  </tr>
			  <tr>
				<td>引出物グループ数：</td>
				<td>
                <?php if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){?>
                <input name="num_gift_groups" <?=$ro?> type="text"  onlyNumeric="i" id="num_gift_groups" size="10" maxlength='1' value="<?=$gift_criteria_data_row[0]['num_gift_groups']?>"  />
		&nbsp;種類（最大7グループまで）
                <?
				}else{
					?>
					<?=$gift_criteria_data_row[0]['num_gift_groups']?>&nbsp;種類 <!-- UCHIDA EDIT 11/08/08 メッセージ変更  -->
					<?
					}
				?>


        </td>
			  </tr>
			  <tr>
				<td valign="top">グループ記号　　：</td>
				<td>
				<?
				$xx = 1;
				$cont=1;
			foreach($data_rows as $row)
			{

				if($cont == 4)
				{
                if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){
				    echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='1' size='6' value='".$row['name']."'>";
				}else{
					echo "<div style='float:left;margin-right:10px; margin-bottom:4px;'>".$row['name']."";
					}

				echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
					echo '<div style="clear:both;"> </div>';
				   $cont=0;
				}
				else
				{

                if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){

				echo "<div style='float:left;margin-right:10px;margin-bottom:4px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='1' size='6' value='".$row['name']."'>";
				}else{
				echo "<div style='float:left;margin-right:10px;margin-bottom:4px;'>".$row['name']."";
					}


				echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
				}

				$xx++;
//				$cont++; 　UCHIDA EDIT 11/08/01 行換えを抑制
			}

		  ?>
				</td>
			  </tr>
			  <tr>
				<td>発注締切日　　　：</td>
				<td>
                <?php if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){?>
                <input name="order_deadline" <?=$ro?> type="text"  onlyNumeric="i" id="order_deadline" size="10" value="<?=$gift_criteria_data_row[0]['order_deadline']?>" />
			&nbsp;(日前)
                <?
				}else{
					?>
					<?=$gift_criteria_data_row[0]['order_deadline']?>&nbsp;(日前)
					<?
					}
				?>


            </td>
			  </tr>
			  <tr>
				<td colspan="2">
				<div style="margin:5px;float:left; padding-left:95px; clear:both;">
					<?php if($gift_criteria_num>0)
					{?>
<!--
					<input type="button" onclick="validForm(<?=$xx?>;" name="id" value="保存" style=" <?=$btn_disp?>" />
-->
					<input type="hidden" name="update" value="update" />
					<input type="hidden" name="id" value="<?=$gift_criteria_data_row[0]['id']?>" />
					<a href="#" onclick="validForm(<?=$xx?>);"><img src="img/common/btn_regist.jpg" alt="登録" width="82" height="22" style=" <?=$btn_disp?>"  /></a>

					<?php }
					else
					{?>
					<input type="hidden" name="insert" value="insert">
					<a href="#" onclick="validForm(<?=$xx?>);"><img src="img/common/btn_regist.jpg" alt="登録" width="82" height="22" style=" <?=$btn_disp?>"  /></a>
					<?php }?>
					</div>
				</td>
			  </tr>

			</table>


			</form>
	</div>
	<!--<div style="float:left;border:1px solid gray;">
		<h2>編集　引出物グループ名</h2>
		<div style="padding-left:10px;padding-bottom:10px;padding-right:10px;">
			<?php
				$group_sql ="SELECT * FROM spssp_gift_group_default  ORDER BY id asc ;";
				$data_rows = $obj->getRowsByQuery($group_sql);

			?>
			<form action="gift.php" method="post" name="editUserGiftGroupsForm">
		  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
		  <?php
			$xx = 1;
			foreach($data_rows as $row)
			{
				echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='2' size='6' value='".$row['name']."'>";
				echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";

				$xx++;
			}

		  ?>
		   <br />
           &nbsp;&nbsp;&nbsp;&nbsp;<font color="red">※半角2文字以内、全角1文字で入力してください</font>
           <br />

		   <input type="button" style=" <?=$btn_disp?>" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm(<?=$xx?>);">
		   </form>

		</div>

	</div>-->
</div>
<div style="clear:both;"></div>
	<br />
<br />
<div style="width:1035px;">
     <h2>料理設定
</h2>
	<form  action="gift.php" method="post" name="menu_criteria_form">
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100">子供料理　　　　：</td>
    <td>
    <?php if(($_SESSION['user_type']==111)||(($_SESSION['user_type']==333))){?>
    <input name="num_menu_groups" type="text" id="num_menu_groups" size="10" <?=$ro?> value="<?=$menu_criteria_data_row[0]['num_menu_groups']?>" />
      種類(最大3つまで)
      <?
	}else{
		echo $menu_criteria_data_row[0]['num_menu_groups']."&nbsp;種類"; // UCHIDA EDIT 11/08/08 メッセージ変更
		}
	  ?>
      </td>
  </tr>
  <tr>
    <td colspan="2">
	<div style="padding-left:100px; margin-top:3px;">
<?php if($menu_criteria_num>0)
{?>

<input type="hidden" name="update2" value="update" />
<input type="hidden" name="id" value="<?=$menu_criteria_data_row[0]['id']?>" />
<a href="#" onclick="validForm2();"><img src="img/common/btn_regist.jpg" alt="登録" width="82" height="22" style="<?=$btn_disp?>" /></a>
<?php }
else
{?>
<input type="hidden" name="insert2" value="insert">
<a href="#" onclick="validForm2();"><img src="img/common/btn_regist.jpg" alt="登録" width="82" height="22" style="<?=$btn_disp?>" /></a>
<?php }?>
</div>

	</td>
  </tr>
</table>





	</form>
	</div>
    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
