<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$get = $obj->protectXSS($_GET);

	$user_id = (int)$_GET['user_id'];

	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");

	$query_string="SELECT * FROM spssp_gift_item_default  ORDER BY id ASC";
	$gift_rows = $obj->getRowsByQuery($query_string);
	$query_string="SELECT * FROM spssp_gift_group_default  ORDER BY id ASC";
	$data_rows = $obj->getRowsByQuery($query_string);

	//echo "<pre>";
	//print_r($data_rows);

	$num_user_gift_group = $obj->GetNumRows("spssp_gift_group","user_id = ".$user_id);
	if($num_user_gift_group <=0)
	{
		foreach($data_rows as $gr)
		{
			unset($gr['id']);
			$gr['user_id'] = $user_id;
			$lid = $obj->InsertData("spssp_gift_group", $gr);
		}
	}

	$num_user_gift = $obj->GetNumRows("spssp_gift","user_id = ".$user_id);

	if($num_user_gift <= 0)
	{
		foreach($gift_rows as $gf)
		{
			unset($gf['id']);
			$gf['user_id'] = $user_id;

			$lgid = $obj->InsertData("spssp_gift", $gf);
		}
	}
	$data_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id." order by id asc");
	$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id." order by id asc");

	if($_POST['submitok']=='OK')
	{
	 	$post = $obj->protectXSS($_POST);
	 	$sql = "delete from spssp_gift_group_relation where user_id=".$user_id;
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
	}



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
	}


	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		if(!empty($staff_users))
		{
			if(in_array((int)$get['user_id'],$staff_users))
			{
				$var = 1;
			}
			else
			{
				$var = 0;
			}
		}

	}
	else
	{
		$var = 1;
	}
?>

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
        <a href="login.html"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
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
<div style="clear:both;"></div>
	<div id="contents">
    <div style="font-size:14; font-weight:bold;">
           <?=$user_row['man_firstname']?> 様・ <?=$user_row['woman_firstname']?> 様
    </div>
 <h2>
<!--             	 <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報 &raquo; 席次表・引出物発注 &raquo; 引出物設定 -->
            	 <a href="manage.php">ＴＯＰ</a> &raquo; 席次表・引出物発注
        </h2>
      <div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi">
      	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
      		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
        </a>
      </div>
        	<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>"><img src="img/common/navi04_on.jpg" width="150" height="22" /></a></div>
        	<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>

      <br />
	<div>
     <a href="new_guest.php?user_id=<?=$user_id?>"> <b>招待者登録</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <b>引出物設定</b>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <a href="menu_user.php?user_id=<?=$user_id?>"> <b>料理設定（子供料理）</b></a>


      <br /><br />
      <div style="clear:both;"></div>

	  <a href="javascript:void(0);" onclick="editUserGiftGroups(<?=$user_id?>);"><b>編集　引出物グループ名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
	  <a href="javascript:void(0);" onclick="editUserGiftItems(<?=$user_id?>);"><b>編集　引出物アイテム名</b></a>
	  <br /><br />
	  </div>
	  <div id="editUserGiftGroups" style="display:none;padding:20px;border:1px solid #CCCCCC;">
	  <form action="ajax/editUserGiftGroups.php?user_id=<?=$user_id?>" method="post" name="editUserGiftGroupsForm">
	  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
	  <?php
	  	$xx = 1;
		foreach($data_rows as $row)
		{
			echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='name".$xx."' name='name".$xx."' value='".$row['name']."'>";
			echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
			$xx++;
		}

	  ?>
	   <br /><br /><br />
	   <div>
	   <input type="button" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm(<?=$xx?>);"></div>
	   </form>
	   <br />
	  </div>
	  <div id="editUserGiftItems" style="display:none;padding:20px;border:1px solid #CCCCCC;">
	   <form action="ajax/editUserGiftItems.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm">
	   <input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate">
	   <?php
	  	$yy = 1;
		foreach($gift_rows as $gift_name)
		{
		echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='item".$yy."' name='name".$yy."' value='".$gift_name['name']."'>";
		echo "<input type='hidden' name='fieldId".$yy."' value='".$gift_name['id']."'></div>";
		$yy++;
		}

	  ?>

	  <br /><br /><br /><br />
	  <div>
	  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm(<?=$yy?>)"></div>
	  </form>
	  <br />



	  </div><br /><br />
	  <div style="clear:both;"></div>
<h2>引出物商品数詳細</h2>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<h3>引出物グループ別総数</h3>
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
			<?php
            	$count=0;

				foreach($data_rows as $row)
				{
			?>
            	<td align="center" style="text-align:center"><b><?=$row['name']?></b></td>
            	<?php
				}
			?>
          </tr>
          <tr style="background:#ECF4FB">
          <?php

			foreach($data_rows as $row)
				{

					$query="select distinct(guest_id) from spssp_guest_gift where group_id='".$row[id]."'";
					$result=mysql_query($query);
					$count=mysql_num_rows($result);
			?>
            		<td align="center" style="text-align:center"><b><?=$count?></b></td>
            <?php
				}
			?>

          </tr>
  </table>
  <h3>引出物商品別総数</h3>

  <table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
			<?php
            	$count=0;

				foreach($gift_rows as $gift_name)
				{
			?>
            <td align="center" style="text-align:center"><b><?=$gift_name['name']?></b></td>
            <?php
				}
			?>
          </tr>
          <tr style="background:#ECF4FB">
          <?php

			foreach($gift_rows as $gift_name)
				{

					$query="select * from spssp_guest_gift where gift_id='".$gift_name[id]."'";
					$result=mysql_query($query);
					$count=mysql_num_rows($result);
			?>
            		<td align="center" style="text-align:center"><b><?=$count?></b></td>
            <?php
				}
			?>

          </tr>
  </table>
  </div>


<h2>引出物商品別グループ設定</h2>


<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='dashboard.php'>Home</a></li>  <li><h2> > Guest Gifts</h2></li> <li><a href='logout.php'>Log Out</a></li>   </ul>");
});
</script>

<script type="text/javascript">
var count=<?=count($data_rows)?>;

function editUserGiftGroups(id)
{
	$("#editUserGiftGroups").toggle('slow');
	$("#editUserGiftItems").hide("slow");
}
function editUserGiftItems(id)
{
	$("#editUserGiftItems").toggle('slow');
	$("#editUserGiftGroups").hide("slow");
}
function checkGroupForm(x)
{	//alert(x);

	for(var y=0;y<x;y++)
	{
		if($("#name"+y).val()=="")
		{
			alert("引出物グループ名が未入力です");
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
			alert("引出物グループ名が未入力です");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserGiftItemsForm.submit();
	}
}
function validForm()
{


	for(var i=0;i<count;i++)
	{
		if (!isCheckedById("group_"+i))
		{
			alert ("商品のグループ登録を最低1つは選択してください");
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

</script>
<?php
if(count($relation_rows)>0)
{
?>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table align="center" cellspacing="0" cellpadding="3" border="0" class="grid">
<?php
$i=0;
foreach($data_rows as $group)
{
?>
<tr height="20px" <?php if($i%2==0){?>style="background:#ECF4FB"<?php }else{?>style="background:#FFFFFF"<?php }?>><td width="5%"><b><?=$group['name'];?></b></td>
<td width='95%'>
<?php
$query_string="SELECT gift_id FROM spssp_gift_group_relation where user_id=".$user_id." and group_id=".$group[id];

$result_gift= $obj->getRowsByQuery($query_string);


foreach($result_gift as $value)
{
 $gift_id=explode("|",$value['gift_id']);
 $gift_ids = implode(",",$gift_id);

 $item_names = $obj->GetSingleData("spssp_gift" , "group_concat(name separator ' ・ ') as names" , " id in ( $gift_ids )");
 echo $item_names;

}
?>
</td>
</tr>
<?php
$i++;
}
?>
</table>
</div>
<?php
}
?>

<form action="gift_user.php?user_id=<?=$user_id?>" method="post" name="gift_form">
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head" height="20px">
     		<td bgcolor="#E1ECF7" align="center" style="text-align:center"><b>商品名</b></td>
            <?php

			foreach($data_rows as $row)
			{
			?>
        		<td  bgcolor="#E1ECF7" rowspan="2" align="center" style="text-align:center"><b><?=$row['name']?></b></td>
            <?php
			}
			?>


		</tr>
		<tr>
		<?php


		$i=0;
		foreach($gift_rows as $gift)
				{
			?>
            <tr height="20px" <?php if($i%2==0){?>style="background:#FFFFFF"<?php }else{?>style="background:#ECF4FB"<?php }?>>
			<td align="center" style="text-align:center"><b><?=$gift['name']?></b></td>
            <?php
			$j=0;
            foreach($data_rows as $row)
				{
			?>
            <td align="center" style="text-align:center"><input type="checkbox" value="<?=$gift['id']?>" id="group_<?=$j?>" name="group_<?=$row['id']?>[]"
			<?php if(is_array($realtion_array)){ if(in_array($gift['id'],$realtion_array['group_'.$row['id']])) { ?> checked="checked" <?php }} ?>  /></td>
            <?php
				$j++;
                }
			?>

            </tr>
			<?php
			$i++;
				}
			?>
            <tr>
            <td colspan="<?php echo count($data_rows)+1;?>">
            <input type="hidden" name="submitok" value="OK" />
            <?php if($var == 1){?><input type="button" name="sub" value="保存" onclick="validForm();" /><?php }?></td>
            </tr>


</table>
</div>
</form>
	<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
		<h3>料理設定（子供料理）</h3>
        <?php
			$query_string="SELECT * FROM spssp_menu_group where user_id =".$user_id."  ORDER BY name ASC;";
			$data_rows = $obj->getRowsByQuery($query_string);
			$j = 1;
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
            <div class="<?=$class?>">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width="10%"><?=$j?></td>
                        <td  align="left"><?php echo $row['name'];?></td>

               		</tr>

                </table>
            </div>
		<?php $i++;$j++; }?>
    </div>

    </div>


</div>

<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
