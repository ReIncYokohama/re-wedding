<?php
include_once("admin/inc/class_information.dbo.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DataClass();
$objInfo = new InformationClass();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

//tabの切り替え
$tab_hikidemono = true;

$TITLE = "引出物・料理の登録 - ウエディングプラス";
include_once("inc/new.header.inc.php");

$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);
$data_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".(int)$_SESSION['userid']." order by id asc");

$data_rows_gift = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".(int)$_SESSION['userid']." order by id asc");
$editable=$objInfo->get_editable_condition($plan_info);
//登録後のメッセージの表示のフラグ
$save_hikidemono = "false";
if($_GET["save"]) $save_hikidemono = "true";

$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
$count_group = (int)$gift_criteria['num_gift_groups'];


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

     if ($post['timeout']=="timeout")	redirect("logout.php");

   else								redirect("hikidemono.php?save=true");exit;
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

 ?>
 <style>
 .guests_area_L2 {
   float: left;
   width: 600px;
 }
 </style>
 <script type="text/javascript">
   $(function(){
     if(<?php echo $save_hikidemono;?>) alert("引出物グループが保存されました");
   });

 function validForm(gift_cnt)
 {
   var reg = /^[0-9]{1,}$/;

   for(var i=1;i<=gift_cnt;i++){
     var tgt = "yobisu_" + i;
     var el = document.getElementById(tgt)
     if(el && reg.test($(el).val()) == false) {
       alert("予備数は半角数字で入力してください");
       $(el).focus();
       return false;
     }
   }
   for(var i=0;i<7;i++)
   {
     if (!isCheckedById("group_"+i))
     {
       if (timeOutNow==true) {
         alert("商品のグループは最低１つ選択が必要なので、保存できませんでした"); // cronでログアウトされた場合の対応が必要
         location.href = "logout.php";
       }
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

 function user_timeout() {
   clearInterval(timerId);
   if (changeAction==true) {
     timeOutNow=true;
     var agree = confirm("タイムアウトしました。\n保存しますか？");
       if(agree==true) {
         $("#timeout").val("timeout");
         validForm();
       }
       else {
         window.location = "logout.php";
       }
   }
   else {
     alert("タイムアウトしました");
     window.location = "logout.php";
   }
 }

 </script>
 <div id="contents_wrapper" class="displayBox">
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
     <div class="title_bar_txt_L">引出物のグループ登録、および、お子様料理をご確認いただけます</div>
     <div class="title_bar_txt_R"></div>
   <div class="clear"></div>
 </div>

 <div class="cont_area">
 <div class="guests_area_L2">
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

     $gift_cnt = 0;
     foreach($gift_rows as $gift)
     {
       if($gift['name']!="") {
         $gift_cnt++;
         ?>
         <tr height="20px" style="background:#FFFFFF">
           <td align="center" style="text-align:center"><b><?=$gift['name'];?></b></td>
           <td ><div style="text-align:center">
           <?php
           $j=0;
           $gift_arr = array();
           $groups = array();
           for($i=0;$i<$count_group;++$i){
             $row = $group_rows[$i];
             $gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$row['id']);

             $gift_arr = explode("|",$gift_ids);

             if(in_array($gift['id'],$gift_arr))
             {
               array_push($groups,$row['id']);
             }

             if (!$editable) {
               $_disable=" disabled='disabled' ";
               $_readonly="readonly='readonly' style='border: #ffffff; ";
             }else {
               $_disable="";
               $_readonly="";
             }
             if ($row['name']!="") {
             ?>
               <div style="width:50px; float:left; text-align:left; padding:2px;"><b> <?=$row['name']?> </b>
               <input type="checkbox" <?=$_disable?> value="<?=$gift['id']?>" id="group_<?=$j?>" <?php if(in_array($gift['id'],$gift_arr)) { ?> checked="checked" <?php } ?> name="group_<?=$row['id']?>[]" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/></div>
             <?php
             }
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
           if (!$editable) {
             $_readonly.=" background-color: #ffffff; text-align:right; '";
           } ?>
           </div>
         </td>
         <td align="center"><input type="text" id="yobisu_<?=$gift_cnt?>" name="value_<?=$gift['id']?>" value="<?=$num_gifts?>" <?=$_readonly?> size="5" maxlength="2" style="text-align:right;border-style:inset;" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/></td>

       </tr>
       <?php
       }
     } ?>
 </table>
         <input type="hidden" value="OK" name="submitok">
     <?php
     if($editable)
     {?>
       &nbsp;<br />
       <a href="javascript:void(0)" onclick="validForm(<?=$gift_cnt?>);" name="sub">
           <img src="img/btn_save_user.jpg" border="0" /></a>　
             <a href="hikidemono.php" name="cancel">
           <img src="img/btn_rollback_user.jpg" border="0" />
           </a>
           <?php
     }
     ?>
         <input type="hidden" id="timeout" name="timeout" value="" />
 </form>
 </div>
 <br /><br />




 <div  style="clear:both; padding-bottom:20px;"> </div>
 <div class="cont_area2">■ 引出物のグループは次のようになります。この記号でお客様ごとに引出物をお選びください。
         <table width="800" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
         <?php
       $groups = Model_Giftgroup::find_by_user_id($user_id);

for($i=0;$i<$count_group;++$i){
  $group = $groups[$i];
  $gift_names = $group->get_gift_name();
  if ($group['name']!="") echo "<tr><td bgcolor='#FFFFFF' width='30' align='center'>".$group['name']."</td><td align='letf' width='200' bgcolor='#FFFFFF'>".implode("・",$gift_names)."</td></tr>";
}
			?>

        </table>
      </div>
<div class="clear">

</div>
</div>

<div class="cont_area">■ お子様用の料理は下記の内容となります。

  <table width="800" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
  	<?php

		//$num_groups = count($menu_groups);
		foreach($menu_groups as $mg)
		{
			//$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);
	  ?>
	  <?php if ($mg['name']!="") {
	      echo "<tr>";
	      echo "<td width='800' bgcolor='#FFFFFF'>".$mg['name']."</td>";
	      echo "</tr>";
      } ?>
    <?php
    }
	?>

  </table>
</div>
</div>
</div>

<?php
include("inc/new.footer.inc.php");
?>
