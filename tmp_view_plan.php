<?php

include_once("admin/inc/class_data.dbo.php");

$data_class = new DataClass;
$user_id = $_SESSION["userid"];

$table_data = $data_class->get_table_data_detail($user_id);

//print_r($table_data);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/tmpl.css" rel="stylesheet" type="text/css" />
<script src="js/json2.js"></script>
<script src="js/jquery-1.5.js"></script>
<script src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="js/jquery.tipTip.js"></script>
<script src="js/util.js"></script>

<script src="js/make_plan.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="js/tipTip.css" rel="stylesheet" type="text/css" />
<link href="css/make_plan.css" rel="stylesheet" type="text/css" />

<script>

$(function(){
  _data = new _Data();
  _data.load(<?php echo json_encode($table_data); ?>);
  _data.guest_view();
  _data.table_view_all();
  _data.set_seat_event();
  //ここの処理は重たいので、後回しにした。
  _data.set_position();
});


</script>

</head>

<body unselectable="on" style="-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;user-select:none;">
<script>
</script>

<div id="header" class="clearfix">
<div class="tab page_name">top Page</div>
<div class="tab view_change_box">View</div>
<?php
  if($data_class->get_table_editable($user_id)){
?>
<div id="save_button" class="tab save_box">保存</div>
<div id="cancel_button" class="tab cancel_box">元に戻す</div>
<?php
  }
?>
</div>
<div style="text-align:center;color:navy;font-size:16px;">座席表編集モード</div>
<div id="contents">

<div id="guest_list">
<div style="text-align:center;">
  <div id="add_mode" mode="add" class="mode selected">追加モード</div>
  <div id="delete_mode" mode="delete" class="mode">削除モード</div>
  <div id="replace_mode" mode="replace" class="mode">入替モード</div>
</div>
<table id="guest_table">
<thead>
<tr>
  <th class="sex guest_sort" sort="sex">郎婦</th>
  <th class="type guest_sort" sort="guest_type">区分</th>
  <th class="name guest_sort" sort="last_name">姓名</th>
</tr>
<!--<tr><td>NO</td><td>郎婦</td><td>区分</td><td>姓名</td><td>卓名</td></tr>-->
  <tr id="guest_view_copy" style="display:none;">
  <!--<td class="number">NO</td>-->
  <td class="sex"></td>
  <td class="type"></td>
  <td class="name"></td>
  <!--<td class="table_name">卓名</td>-->
</tr>
</thead>
<tbody id="guest_view_tbody">
</tbody>
</table>
</div>
<div id="table_view">
<?php
  $seat_num = $table_data["seat_num"];
  $seat_row = $seat_num/2;
  $table_heigth = ($seat_row+1)*20;
  for($i=0;$i<count($table_data["rows"]);++$i){
    $row = $table_data["rows"][$i];
    $width = 800/count($row["columns"]);
?>
<div style="width:800px;display:left;position:relative;">
    <div style="position:relative;overflow:hidden;<?php echo ($row['ralign']=='C')?'width:'.(800*$row['display_rate']).'px;margin:auto;':'';?>">
<?php
    for($j=0;$j<count($row["columns"]);++$j){
      $column = $row["columns"][$j];
      $column_num = $row['display_num'];
      $table_name = $column["name"];
      $table_id = $column["id"];
      $visible = $column["visible"];
      if($row["ralign"] == "C" && $column["display"] == 0 && !$visible) continue;
?>
<div id="table<?=$table_id?>" style="width:<?=$width;?>px;height:<?=$table_height?>px;float:left;<?php echo ($column["visible"])?"visibility:hidden":""?>">
<table class="guest_table">
<thead>
<div style="text-align:center;"><?=$table_name?></div>
</thead>
<tbody>
<?php
    for($k=0;$k<$seat_row;++$k){
?>
<tr>
<?php
if(!$visible){
?>
<td table_id="<?=$table_id?>" id="<?php echo $column["seats"][$k*2]["id"];?>" seat_id="<?php echo $column["seats"][$k*2]["id"];?>" class="seat seat<?php echo $k*2;?> tooltip"></td>
<td table_id="<?=$table_id?>" id="<?php echo $column["seats"][$k*2+1]["id"];?>"seat_id="<?php echo $column["seats"][$k*2+1]["id"];?>" class="seat seat<?php echo $k*2+1;?> tooltip"></td>
<?php
}else{
?>
<td></td><td></td>
<?php
}
?>
</tr>
<?php
    }
?>
</tbody>
</table>
</div>
<?
    }
?>
    </div>
</div>
<?php
  }
?>
</div>
</div>
<div id="move_card">da</div>
<div id="guest_detail"><image/></div>
</body>
</html>
