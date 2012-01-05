<?php
require_once("inc/class_hotel.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new HotelDBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");
$hotelArray = $obj->getHotelArray();

//update
if($post["maintenance_id"]){
  $data = array();
  $data["title"] = $post["title"];
  $data["description"] = $post["description"];
  sort($post["hotels"]);
  $data["hotel_ids"] = implode(",",$post["hotels"]);
  $data["display"] = 1;
  $obj->UpdateData("spssp_maintenance", $data, " id = ".$post['maintenance_id']);
}else if($_GET["hide"]){
  $data = array();
  $data["display"] = 0;
  $obj->UpdateData("spssp_maintenance", $data, " id = ".$_GET['maintenance_id']);
//create
}else if($post["title"] or $post["description"]){
  $data = array();
  $data["title"] = $post["title"];
  $data["description"] = $post["description"];
  sort($post["hotels"]);
  $data["hotel_ids"] = implode(",",$post["hotels"]);
  $lsid = $obj->InsertData("spssp_maintenance", $data);
}
$maintenanceArray = $obj->getMaintenanceArray();

$maintenance_data = $obj->getSingleRow("spssp_maintenance"," id=".(int)$_GET["maintenance_id"]);
$update = false;
$maintenance_data_hotels = array();
if($maintenance_data){
  $update = true;
  $maintenance_data_hotels = explode(",",$maintenance_data["hotel_ids"]);
}
?>
<style>
.new_super_message
{
	width:300px;
	display:none;
}
.super_desc
{
padding:5px 5px 5px 147px;;
display:none;
color:#000000;
font-weight:normal;
}
</style>

  <div id="topnavi">
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      <h2>ホテルへのメンテナンス内容編集</h2>
      <div class="txt2"> <!-- <p><a href="javascript:void()" onclick="add_supper_message();">メッセージ作成 </a></p>-->
        メンテナンス内容作成<br /> 
        <form method="post" name="maintenance_form">

        <table cellpadding="5" cellspacing="1" border="0" align="left">
			<tr>
			  <td width="50" align="left" nowrap="nowrap"><font size="2">タイトル</font></td>
				<td width="5" align="center" nowrap="nowrap"> <font size="2">：</font></td><td width="750" align="right"> <input type="text" name="title" id="super_title" style="width:750px;" value="<?=$maintenance_data["title"]?>"/></td>
			</tr>
			<tr>
			  <td align="left" nowrap="nowrap"><font size="2">本文</font></td>
				<td align="center" nowrap="nowrap"><font size="2">：</font></td>
				<td width="750" align="right"> <textarea name="description" cols="40" rows="15" id="super_description" style="width:750px;"  /><?=$maintenance_data["description"]?></textarea><br /><br /></td>
			</tr>
      <tr>
        <td></td><td colspan="2">

        <ul class="ul3" id="message_BOX" style="overflow:auto;">
<table width="660
" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td width="40" valign="middle" nowrap="nowrap">
      <input type="checkbox" name="hotels" value="1"/>&nbsp;All
    </td>
    <td width="100" align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
    <td width="20" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    <td width="500" nowrap="nowrap">&nbsp;</td>
  </tr>

<?php
foreach($hotelArray as $hotel){
  $key = in_array($hotel["id"], $maintenance_data_hotels);
?>
  <tr>
    <td valign="middle" nowrap="nowrap">
     <input type="checkbox" name="hotels[]" value="<?=$hotel["id"]?>" <?php echo ($key)?"checked":"";?>/>
    </td>
    <td width="100" align="right" valign="middle" nowrap="nowrap"><?=$hotel["hotel_code"]?></td>
    <td width="20" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    <td width="500" nowrap="nowrap"><?=$hotel["hotel_name"]?></td>
  </tr>
<?php
}
?>
</table>
		</ul>

        </td>
      </tr>

			<tr>
			  <td nowrap="nowrap">&nbsp;</td>
				<td>&nbsp; </td>
				<td>
					<img alt="メンテナンス内容送信" src="img/btn_maintenance_on_admin.jpg" onclick="save();">
                   <img border="0" src="img/common/btn_clear_admin.jpg" alt="クリア" onclick="clear_form();">
		      <input type="hidden" name="maintenance_id" value="<?=$maintenance_data["id"]?>"/>
				<br />
				<br />
				</td>
			</tr>
		</table>
		</form>
        <p></p>
      </div>
<div>
   <ul class="ul3" id="message_BOX" style="overflow:auto;">
<table width="800
" border="0" cellspacing="0" cellpadding="0">

<?php
foreach($maintenanceArray as $maintenance){
?>
  <tr>
    <td width="100" nowrap="nowrap"><?=$maintenance["id"]?></td>
    <td width="400" nowrap="nowrap"><?=$maintenance["title"]?></td>
    <td width="100" align="right" valign="middle" nowrap="nowrap"><a href="?maintenance_id=<?=$maintenance["id"]?>">編集</a></td>
    <td width="100" align="right" valign="middle" nowrap="nowrap">
<?php
if($maintenance["display"]){
?>
<a href="?maintenance_id=<?=$maintenance["id"]?>&hide=true">解除</a>
<?php }else{ ?>
解除済
<?php } ?>
</td>
  </tr>
<?php
}
?>
</table>
		</ul>

</div>
    </div>
  </div>
<?php
  include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
<script type="text/javascript">
function save(){
	document.maintenance_form.submit();  
}
function clear_form(){
  location.href = "?";
}
/*function add_supper_message()
{
	$j(".new_super_message").toggle("slow");
	$j("#edit_sp").val('');
}*/
function save_super_message()
{
	var title = $j("#super_title").val()
	var desc = $j("#super_description").val();
	if(title == '')
	{
		alert("タイトルが未入力です");
		$j("#super_title").focus();
		return false;
	}
	if(desc == '')
	{
		alert("本文が未入力です");
		$j("#super_description").focus();
		return false;
	}


}
/*function cancel_super_message()
{
	$j("#edit_sp").val('');
	$j(".new_super_message").fadeOut(500);
}
*/
function view_dsc_super(id)
{
	$j("#super_desc_"+id).toggle();

}
function edit_super_msg(id)
{

	var mid = id;
	$j.post('manage.php',{'ajax':'ajax','id':mid}, function(data){

		$j("#edit_sp").val(id);
		$j("#save_super").val('');
		var arr = data.split(",");
		$j("#super_title").val(arr[0]);
		$j("#super_description").val(arr[1]);
		$j("#super_desc_"+id).fadeOut(100);
		$j(".new_super_message").fadeIn(500);
	});


}

function viewMsg(id)
{
	$j.post('ajax/view_user_message.php', {'id':id},function(data) {

	});

	user_a_id= $j("#desc_"+id+" input").val();

	$j("#desc_"+id).dialog("open");

}


function clearSubmit()
{
   document.getElementById('super_title').value='';
   document.getElementById('super_description').value='';
}

</script>