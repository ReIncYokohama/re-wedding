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
<script src="js/jquery-1.5.js"></script>
<script src="js/jquery-ui-1.8.16.custom.min.js"></script>

<script src="js/make_plan.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/make_plan.css" rel="stylesheet" type="text/css" />

<script>

$(function(){
  _data = new _Data();
  _data.load(<?php echo json_encode($table_data); ?>);
  _data.guest_view();
  _data.table_view_all();
  _data.set_seat_event();
});


</script>

</head>

<body>
<script>
</script>

<div id="header" class="clearfix">
<div class="page_name">top Page</div>
<div class="view_change_box">View</div>
<div class="cancel_box">Save</div>
<div class="save_box">Cancel</div>
</div>
<div style="text-align:center;">座席表編集モード</div>
<div id="contents">

<div id="guest_list">
<div style="text-align:center;"><div class="mode selected">追加モード</div><div class="mode">削除モード</div></div>
<table id="guest_table">
<thead>
<tr><td class="sex">郎婦</td><td class="type">区分</td><td class="name">姓名</td></tr>
<!--<tr><td>NO</td><td>郎婦</td><td>区分</td><td>姓名</td><td>卓名</td></tr>-->
  <tr id="guest_view_copy" style="display:none;">
  <!--<td class="number">NO</td>-->
  <td class="sex">郎婦</td>
  <td class="type">区分</td>
  <td class="name">姓名</td>
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
      $table_name = $row["table_name"]."テーブル名";
      $table_id = $column["id"];
      if($row["ralign"] == "C" && $column["display"] == 0 && !$column["visible"]) continue;
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
<td table_id="<?=$table_id?>" seat_id="<?php echo $column["seats"][$k*2]["id"];?>" class="seat seat<?php echo $k*2;?>"></td>
<td table_id="<?=$table_id?>" seat_id="<?php echo $column["seats"][$k*2+1]["id"];?>" class="seat seat<?php echo $k*2+1;?>"></td>
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
<div id="">あさ</div>
</body>
</html>
