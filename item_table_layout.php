
<div id="table_view" style="margin:auto;width :100%;">
<div style="margin:0 auto;width:100px;display:block;">
<div  style="margin: auto;width:80x;border:1px solid gray;white-space:nowrap;text-align:center; ">
<?php if ($table_data["layoutname"]!="") print $table_data["layoutname"]; else echo "&nbsp;&nbsp;&nbsp;";?>
</div>
              </div>
<?php
      
   $table_height = "31";
$display_number = $item_table_layout_number?true:false;
$num = 0;
  for($i=0;$i<count($table_data["rows"]);++$i){
    $row = $table_data["rows"][$i];
    $width_all = 35*count($row["columns"]);
    $width = 31;
?>
<div style="width:<?php echo $width_all; ?>px;display:left;position:relative;margin:auto;">
    <div style="position:relative;overflow:hidden;<?php echo ($row['ralign']=='C')?'width:'.($width_all*$row['display_rate']).'px;margin:auto;':'';?>">
<?php
   
    for($j=0;$j<count($row["columns"]);++$j){
      $column = $row["columns"][$j];
      $column_num = count($row["columns"]);
      $table_id = $column["id"];
      $visible = $column["visible"];
      $dis = "";
      if($visible and $column["display"]==0){
        $dis = "visibility:hidden;";
      }else if ($column["display"]==0){
        $dis = "display:none;";
      }
      ?>
      <div id="table<?=$table_id?>" class="tables" style="width:<?=$width;?>px;height:<?=$table_height?>px;float:left;<?php echo $dis;?>;margin:2px;"><p>
      <?php  
      if(!$column["visible"]) ++$num;
      if($display_number) echo "<span style=\"font-size:11px;color:red;\">".$num."</span>";
      $_nm2 = mb_substr($column["name"], 0,2,'UTF-8');
      $_han = 1;
      if (preg_match("/^[a-zA-Z0-9]+$/", $_nm2)) $_han = 2; // 先頭の２文字が全て半角
      echo mb_substr ($column["name"], 0,$_han,'UTF-8');
      ?>
      </p>
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
</div>
