<?php

include_once("admin/inc/class_data.dbo.php");

$data_class = new DataClass;
$user_id = Core_Session::get_user_id();
$table_data = $data_class->get_table_data_detail($user_id);
$plan = Model_Plan::find_one_by_user_id($user_id);

$takasago_guest_obj = $plan->get_takasago_guest_obj();
foreach($takasago_guest_obj as $guest){
  $namecard_url = $guest->get_image("namecard.png");
  $fullname_url = $guest->get_image("thumb1/guest_fullname.png");  
  $guest->_td_html = '<td align="center" class="tooltip" title="<image src=\''.$namecard_url.'\'>" valign="middle" style="text-align:center; padding:7px;width:100px;"><image src="'.$fullname_url.'"></td>';
}
$html.='
<table align="center" cellspacing="2"><tr>
'.($takasago_guest_obj[3]?$takasago_guest_obj[3]->_td_html:"").'
'.($takasago_guest_obj[1]?$takasago_guest_obj[1]->_td_html:"").'
'.($takasago_guest_obj["left"]?$takasago_guest_obj["left"]->_td_html:"").'
'.($takasago_guest_obj[5]?$takasago_guest_obj[5]->_td_html:"").'
'.($takasago_guest_obj["right"]?$takasago_guest_obj["right"]->_td_html:"").'
'.($takasago_guest_obj[2]?$takasago_guest_obj[2]->_td_html:"").'
'.($takasago_guest_obj[4]?$takasago_guest_obj[4]->_td_html:"").'
</tr></table>';

$layoutname = $table_data["layoutname"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/tmpl.css" rel="stylesheet" type="text/css" />
<script src="js/libs/jquery.js"></script>
<script src="js/libs/json2.js"></script>
<script src="js/libs/underscore.js"></script>
<script src="js/libs/backbone.js"></script>
<script src='js/make_plan_old.js'></script>
<link href="css/main.css" rel="stylesheet" type="text/css"/>
<link href="css/make_plan_old.css" type="text/css" rel="stylesheet">

<script>
$(function(){
  Re.usertable.load(<?php echo json_encode($table_data); ?>);
  var view = new Re.views.make_plan();
  view.show_left_sidebar();
  view.set_seats();
});
</script>
<style>
.clearfix:after {
  content: "\0020";
  display: block;
  height: 0;
  clear: both;
  overflow: hidden;
  visibility: hidden;
}
.rows
{
float:left;

clear:both;
width:100%;
}
.tables
{
float:left;
width:198px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
}

table a:link {
	color: #F90;
	text-decoration:underline;
}
table a:visited {
	color: #F90;
	text-decoration:underline;
}
table a:hover {
	color: #C30;
	text-decoration:underline;
}
table a:active {
	color: #C30;
	text-decoration:underline;
}

#room
{

width:auto;

}
.droppable
{
width:80px;
height:30px;
}
.seat_droppable
{
width:80px;
height:30px;
float:left;
margin:0px;
padding:0px;
}
.vertical {
width: 430px;
height: 180px;
writing-mode: tb-rl;
-webkit-writing-mode : vertical-rl ; 
-moz-writing-mode: vertical-rl;
direction: ltr;
 }
.make_plan_main_contents{
  width:1220px;
}
.make_plan_main_title{
  width:1220px;
}
.make_plan_main_left{
  width:270px;
  float:left;
}
.make_plan_main_right{
  width:900px;
  float:left;
}
#contents_right{
width:1220px;
}
#contents_wrapper{
width:1200px;
padding:0px;
margin:0px;
}
body{
  background:none;
}
#make_plan_table{
width: 965px;
}
.title_bar.main_plan{
 width:980px;
}
</style>

</head>

<body unselectable="on" style="-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;user-select:none;">

<div id="contents_wrapper" class="displayBox">

  <div id="contents_right">
    <div class="title_bar main_plan">
      <div class="title_bar_txt_L">席次表を編集</div>
      <div class="clear"></div>
    </div>
    <div class="cont_area"  align="center"></div>

    <div class="make_plan_main_contents" id="con_area_ie">
      <div id="side_area" sytle="padding-right:0px;width:350px;">
        <div align="right"><a href="make_plan_full.php"><image src="img/btn_sort_free_user.jpg"></a></div>
        <div  id="guests_conatiner" style="float:left; height:710px; width:100%; overflow-x:hidden;overflow-y:scroll;" >
          <table id="left_sidebar_table" class="left_sidebar">
            <thead>
              <tr>
                <th class="no">No</th>
                <th class="sex">
                  <a>郎婦↓</a>
                </th>
                <th class="group">
                  <a>
                    区分↓</a>
                </th>
                <th class="name">姓名</th>
                <th class="tablename">卓名</th>
              </tr>
            </thead>
            <tbody id="left_sidebar"></tbody>
          </table>
        </div>
      </div>

      <div class="make_plan_main_right">
  	    <form action="insert_default_plan.php?user_id=<?=(int)$user_id?>&plan_id=<?=$plan_id?>" method="post" id="insert_plan" name="insert_plan">
          <input type="hidden" name="query" value="<?php echo $_SERVER["QUERY_STRING"];?>">
  			  <div align="right">
            <span class="comment"></span>
            <?php if($plan->editable()){ ?>
            <image src="img/btn_save_user.jpg" id="save_button"/>
            <image src="img/btn_rollback_user.jpg" id="reset_button"/>
            <?php }?>
            <image src="img/btn_back_user.jpg" id="back_button"/>
          </div>
          <br/>
          <div id="make_plan_table">
  			    <div id="room" style="float:left; width:<?=$width?>px;">
			      <div align="center" style="text-align:center; margin:0 auto; font-size:13px; font-size:13px">
				      <?=$html?>
			      </div><br/>
			      <div align="center" style="height:20px; text-align:center; padding:5px; margin:0 auto; font-size:13px">
              <div style="width:500px; border:1px solid black;margin:auto;">
				        <?=$layoutname?>
              </div>
			      </div>
            <div id="toptst" style="float:left; width:100%; ">
					    <?php
              foreach($table_data["rows"] as $row){
                $width = count($row["columns"])*215;
                if($row["ralign"] == "C"){
                  $con_width = $row["display_num"]*215;
                  $pos = 'margin:0 auto; width:'.$con_width.'px';
                }else{
                  $pos = 'float:left;width:'.$width.'px;';
                }
                ?>
                <div class="rows" style="width:<?php echo $width;?>px;">
                  <div class="row_conatiner" style="<?=$pos;?>">
                  <?php
                  $index = 0;
							    foreach($row["columns"] as $column)
							    {
                    $tblname = $column["name"];
                    $table_id = $column["id"];
                    $dis = "";
                    $visible = $column["visible"];
                    if($visible and $column["display"]==0){
                      $dis = "visibility:hidden;";
                    }else if ($column["display"]==0){
                      $dis = "display:none;";
                    }
                    
                    ++$index;
                    ?>
                    <div class="make_plan_tables" id="tid_<?=$table_id?>" style="<?=$dis?>margin-left:15px;" >
                      <p align="center" style="text-align:center" id="p_<?=$table_row['id']?>">
                        <b>&nbsp;</b>
                      </p>

                    <?php
  								  $j=1;
	  							  $jor=0;
                    foreach($column["seats"] as $seat){ ?>
                      <div id="<?=$seat['id']?>" seat_id="<?=$seat["id"]?>" class="seat" style="background-color:<?php echo ($index % 2 == 1)?"#F5F8E5":"#e5b9b9";?>;">
					              <div id="abc_<?=$seat['id']?>" class="gallery ui-helper-reset ui-helper-clearfix">
											  </div>
                      </div>
                      <?php if($jor%2==0 and $j==1) { ?>
                      <div class="space tablename">
                            <span class="font08"><?=$tblname?></span>
                      </div>
                      <?php } else if($jor%2==0) { ?>
										  <div class="space"></div>
					  	        <?php }
                      ++$jor;
                      ++$j;
                      ?>
									  <?php }?>
									</div>
                  <?php } ?>
                <div class="clearfix"></div>
                  </div>
                	  </div>
							  <?php }?>
                	  </div>
                	</div>
            	</div>

			<div class="clear" style="float:left; clear:both; height:10px;">&nbsp;</div>
    </div>
    <input type="hidden" id="timeout" name="timeout" value="" />
	</form>
  </div>
  <div class="clear" style="float:left; clear:both; height:10px;"></div></div></div>
  </div>

</body>
</html>
