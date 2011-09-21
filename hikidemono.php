<?php
	include_once("admin/inc/class_information.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	include_once("inc/new.header.inc.php");
	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);
	$data_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".(int)$_SESSION['userid']." order by id asc");

	$data_rows_gift = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".(int)$_SESSION['userid']." order by id asc");
	
	$editable=$objInfo->get_editable_condition($plan_info);
	if($_POST['submitok']=='OK')
	{
	 	$post = $obj->protectXSS($_POST);
	 	$sql = "delete from spssp_gift_group_relation where user_id=".(int)$_SESSION['userid'];
	 	mysql_query($sql);



		foreach($data_rows as $value)
		{
			$postvalue="group_".$value[id];
			if($post[$postvalue])
			{
				$insert_string['gift_id']=implode("|",$post[$postvalue]);
			}
			else{
			$insert_string['gift_id']="";
			}
				$insert_string['user_id']=$user_id;
				$insert_string['group_id']=$value[id];
				$obj->InsertData("spssp_gift_group_relation",$insert_string);

		}
		foreach($data_rows_gift as $value_row)
		{
			$postvalue="value_".$value_row[id];

			$value_string['value']=$post[$postvalue];
			$value_string['item_id']=$value_row[id];

			$sql = "delete from spssp_item_value where item_id=".(int)$value_row[id];
	 		mysql_query($sql);

			$obj->InsertData("spssp_item_value",$value_string);

		}

	redirect("hikidemono.php");exit;
	}
	if(isset($_POST['subgroup']))
	{
	   $ida = explode(',',$_POST['id']);
	   for($i=0; $i < (count($ida)-1); $i++)
	   {
		   $sql = "Update spssp_menu_group set name='".$_POST['menu'][$i]."' where id=".(int)$ida[$i];
		   mysql_query($sql);
	   }

	}

	/*$query_string="SELECT * FROM spssp_gift_item_default  ORDER BY id ASC";
	$gift_rows = $obj->getRowsByQuery($query_string);
	$query_string="SELECT * FROM spssp_gift_group_default  ORDER BY id ASC";
	$data_rows = $obj->getRowsByQuery($query_string);

	$query_string="SELECT * FROM spssp_gift_group_relation where user_id=".$user_id;
	$relation_rows = $obj->getRowsByQuery($query_string);

   if(count($relation_rows)>0)
   	{
		foreach($relation_rows as $value)
		{
			$realtion_array['group_'.$value['group_id']]=explode("|",$value['gift_id']);
		}

		$query_string="SELECT * FROM spssp_guest_gift where user_id=".$user_id;
		$relation_guest = $obj->getRowsByQuery($query_string);
		foreach($relation_guest as $value)
		{
			if(!in_array($value['gift_id'],$realtion_array['group_'.$value['group_id']]))
			{
				$delete_gift="DELETE from spssp_guest_gift where id=".(int)$value['id'];
				mysql_query($delete_gift);


			}

		}

	}
	else
	{
			foreach($data_rows as $value)
			{
			  $realtion_array['group_'.$value['id']]=array();
			}
	}*/



?>
<script type="text/javascript">

 var title=$("title");
 $(title).html("引出物・料理の登録 - ウエディングプラス");

	$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(2)").addClass("active");
	});

function validForm()
{


	for(var i=0;i<7;i++)
	{
		if (!isCheckedById("group_"+i))
		{
			alert ("商品のグループ登録を最低１つは選択してください");
			return false;
		}
	}
	document.gift_form.submit();
}


  function isCheckedById(id)
    {
        var checked = $("input[@id="+id+"]:checked").length;
        if (checked == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

function editUserGiftGroups(id)
{
	$("#editUserGiftGroups").toggle('slow');
	$("#editUserGiftItems").hide("slow");
	$("#menugroup").hide('slow');
}
function editUserGiftItems(id)
{
	$("#editUserGiftItems").toggle('slow');
	$("#editUserGiftGroups").hide("slow");
	$("#menugroup").hide('slow');
}
function checkGroupForm(x)
{	//alert(x);

	for(var y=0;y<x;y++)
	{
		if($("#name"+y).val()=="")
		{
			alert(" ");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserGiftGroupsForm.submit();
	}
}
function checkGiftForm(x)
{	//alert(x);

	for(var y=0;y<x;y++)
	{
		if($("#item"+y).val()=="")
		{
			alert("");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserGiftItemsForm.submit();
	}
}


function editUserMenu()
{
	$("#menugroup").toggle('slow');
	$("#editUserGiftItems").hide('slow');
	$("#editUserGiftGroups").hide("slow");
}

</script><div id="contents_wrapper">
  <div id="nav_left">
    <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><img src="img/step_head_bt02_on.jpg" width="150" height="60" border="0"/></div>
    <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
    <div class="clear"></div>
  </div>
  <div id="contents_right">
<div class="title_bar">

		<!-- UCHIDA EDIT 11/07/26 -->
		<!-- <div class="title_bar_txt_L">引出物の登録、料理の登録を行います。</div> -->
		<div class="title_bar_txt_L">引出物のグループ登録、および、お子様料理をご確認いただけます</div>
		<div class="title_bar_txt_R"></div>
	<div class="clear"></div>
</div>

<div class="cont_area">
<div class="guests_area_L">
<?php
$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
		$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
		$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding:4px;">
	 <!--<a href="javascript:void(0);" onclick="editUserGiftGroups(<?=$user_id?>);"><b>編集　引出物グループ名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
	  <a href="javascript:void(0);" onclick="editUserGiftItems(<?=$user_id?>);"><b>編集　引出物アイテム名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
	  <a href="javascript:void(0);" onclick="editUserMenu();" > <b>料理設定（子供料理）</b></a>-->

	<div id="editUserGiftGroups" style="display:none;padding:20px;border:1px solid #CCCCCC;">
	  <form action="ajax/editUserGiftGroups.php?user_id=<?=$user_id?>" method="post" name="editUserGiftGroupsForm">
	  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>

	  <?php
	  	$xx = 1;
		foreach($data_rows as $row)
		{
			echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='name".$xx."' name='name".$xx."' value='".$row['name']."'>";
			echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
			$xx++;
		}

	  ?>

	 </td>
  </tr>
  <tr>
    <td>
	   <input type="button" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm(<?=$xx?>);">
	   </td>
  </tr>
</table>
	   </form>
	   <br />
	  </div>
	  <div id="editUserGiftItems" style="display:none;padding:20px;border:1px solid #CCCCCC;">
	   <form action="ajax/editUserGiftItems.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm">
	   <input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate">
	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	   <?php
	  	$yy = 1;
		foreach($gift_rows as $gift_name)
		{
		echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='item".$yy."' name='name".$yy."' value='".$gift_name['name']."'>";
		echo "<input type='hidden' name='fieldId".$yy."' value='".$gift_name['id']."'></div>";
		$yy++;
		}

	  ?>
	  </td>
  </tr>
  <tr>
    <td>
	  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm(<?=$yy?>)">

	  </td>
  </tr>
</table>
	  </form>
	  <br />


	  </div>
	   <div id="menugroup" style="display:none;">
	  <form name="menugroup" action="hikidemono.php" method="post">
	   <table width="587" border="0" cellspacing="1" cellpadding="3">
  	<?php

		$num_groups = count($menu_groups);
		foreach($menu_groups as $mg)
		{
			//$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);
		$idall .= $mg['id'].',';
	  ?>
    <tr>
      <td width="62" bgcolor="#FFFFFF">　<input type="text" name="menu[]" value="<?=$mg['name']?>" /></td>

    </tr>

    <?php
    	}

	?>
   <tr>
	<td><input type="submit" name="subgroup" value="保存" /></td>
	</tr>
  </table>
  <input type="hidden" name="id" value="<?=$idall?>" />
  </form>
	  </div>
	</td>
  </tr>
</table>

■ 商品名をご確認のうえ、グループ登録を行ってください。予備（お持ち帰りなど）の設定もできます。
<form action="hikidemono.php" method="post" name="gift_form">
 <table width="800" border="0" cellspacing="1" cellpadding="3" bgcolor="#666666" style="float:left;">
    	<tr>
        	<td width="200" align="center" bgcolor="#FFFFFF">商品名</td>
        	<td width="500" align="center" bgcolor="#FFFFFF">グループ登録</td>
        	<td width="100" align="center" bgcolor="#FFFFFF">予備数</td>

      	</tr>

		<tr>
		<?php


		$i=0;



		foreach($gift_rows as $gift)
				{
			?>
            <tr height="20px" <?php if($i%2==0){?>style="background:#FFFFFF"<?php }else{?>style="background:#ECF4FB"<?php }?>>
			<td align="center" style="text-align:center"><b><?=$gift['name'];?></b></td>
            <td ><div style="text-align:center">
			<?php
			$j=0;
			$gift_arr = array();
			$groups = array();
            foreach($group_rows as $row)
				{
				$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$row['id']);

				$gift_arr = explode("|",$gift_ids);

				if(in_array($gift['id'],$gift_arr))
				{
					array_push($groups,$row['id']);
				}



			?>
            <div style="width:50px; float:left; text-align:left; padding:2px;"><b> <?=$row['name']?> </b> <input type="checkbox" value="<?=$gift['id']?>" id="group_<?=$j?>" <?php if(in_array($gift['id'],$gift_arr)) { ?> checked="checked" <?php } ?> name="group_<?=$row['id']?>[]"/></div>
            <?php
				$j++;
                }

				$num_gifts = 0;
				if(!empty($groups))
				{
					foreach($groups as $grp)
					{
						$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp);
						$num_gifts += $num_guests_groups;
					}
					unset($groups);
				}

				$num_gifts = $obj->GetSingleData("spssp_item_value","value", "item_id = ".$gift['id']);
			?></div>
            </td>
			<td align="center"><input type="text" name="value_<?=$gift['id']?>" value="<?=$num_gifts?>" size="5" maxlength="2" style="text-align:right" /></td>

            </tr>
			<?php
			$i++;
				}
			?>
          <tr>
		     <td colspan="3">
            <input type="hidden" value="OK" name="submitok">
				<?php
				if($editable)
				  {?>
            		<input type="button" onclick="validForm();" value="保存" name="sub">
				<?php
				}
				?>
			</td>
		  </tr>
</table>
</form>
</div>
<br /><br />




<div  style="clear:both; padding-bottom:20px;"> </div>
<div class="cont_area2">■ 引出物のグループは次のようになります。この記号でお客様ごとに引出物をお選びください。
        <table width="800" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
        <?php
			$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
            	foreach($group_rows as $grp)
				{
					$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
					$gift_arr = explode("|",$gift_ids);
					$gift_ids = implode(',',$gift_arr);
					$item_names = $obj->GetSingleData("spssp_gift" , "group_concat(name separator ' ・ ') as names" , " id in ( $gift_ids )");

					echo "<tr><td bgcolor='#FFFFFF' width='30' align='center'>".$grp['name']."</td><td align='letf' width='200' bgcolor='#FFFFFF'>".$item_names."</td></tr>";
				}
			?>

          <tr>
            <td width="20" align="center" bgcolor="#FFFFFF">×</td>
            <td width="780" bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
        </table>
      </div>
<div class="clear">

</div>
</div>

<div class="cont_area">

<?php
if(count($relation_rows)>0)
{
?>

<table width="800" border="0" cellspacing="1" cellpadding="3">
<?php
$i=0;
foreach($data_rows as $group)
{
?>
<tr height="20px" <?php if($i%2==0){?>style="background:#ECF4FB"<?php }else{?>style="background:#FFFFFF"<?php }?>><td width="20" align="center"><b><?=$group['name'];?></b></td>
<td width='780'>
<?php
$query_string="SELECT gift_id FROM spssp_gift_group_relation where user_id=".$user_id." and group_id=".$group[id];
$result_gift= $obj->getRowsByQuery($query_string);


foreach($result_gift as $value)
{
 $gift_id=explode("|",$value['gift_id']);
 foreach($gift_id as $gift)
	{

$gift_name = $obj->GetSingleData("spssp_gift_item_default","name","id=".$gift['gift_id']);
?>
<div style="float:left;margin-left:20px;"><?=$gift_name?></div>
<?php
 }
}
?>
</td>
</tr>
<?php
$i++;
}
?>
<tr>
      <td width="20" align="center" bgcolor="#FFFFFF">×</td>
      <td width="780" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>

<?php
}
?>
</div>
<div class="cont_area">■ お子様用の料理は下記の内容となります。

  <table width="800" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
  	<?php

		//$num_groups = count($menu_groups);
		foreach($menu_groups as $mg)
		{
			//$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);

	  ?>
    <tr>
      <td width="800" bgcolor="#FFFFFF">　<?=$mg['name']?></td>

    </tr>
    <?php
    	}
	?>

  </table>
</div>
</div>
<div style="clear:both;"></div>
</div>

<?php
include("inc/new.footer.inc.php");
?>
