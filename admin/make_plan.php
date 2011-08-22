<?php
session_start();

include_once("inc/dbcon.inc.php");
include_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include_once("inc/plan.new.header.inc.php");

$user_id = (int)$get['user_id'];
$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);

$layout = $obj->getSingleRow("spssp_table_layout", "user_id = ".(int)$user_id." limit 1");


if(empty($layout) )
{
	echo "<script type='text/javascript'> alert('卓レイアウトを選択してください。'); window.location='users.php'; </script>";
	
}

	$plan_id = (int)$get['plan_id'];

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);
	
	
	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;
	
	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;
	
	$room_tables = $plan_row['column_number'];
	$room_width = (int)(184*(int)$room_tables)."px";
	
	
	$row_width = (int)(182*$room_tables);
	$content_width = ($row_width+235).'px';
	
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);

	$itemids = array();
	if(isset($_SESSION['cart']))
	{
		
	}
	else
	{
		$plan_details_row = $obj->GetAllRow("spssp_plan_details"," plan_id=".$plan_id);
		if(!empty($plan_details_row))
		{
			foreach($plan_details_row as $pdr)
			{
				$skey= $pdr['seat_id'].'_input';
				$sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
				$_SESSION['cart'][$skey]=$sval;
			}
		}
	}
	if(isset($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
		{
			if($item)
			{
				$itemArr = explode("_",$item);
				$itemids[] = $itemArr[1];
			}
		}

	}
	include("inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow( "spssp_respect");
	include("inc/return_dbcon.inc.php");
?>
<script type="text/javascript">
$(function(){
$(".rows").filter(":first").css("border-top","1px solid #666666");

$.fx.speeds._default = 400;
});

</script>
<link href="../css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">


function checkConfirm()
{
	var conf;
	conf = confirm('修正内容を保存しても宜しいですか？');
	if(conf)
	{
		document.insert_plan.submit();
	}
	
}

</script>

<style>

.rows
{
float:left;

clear:both;
width:100%;
}
.tables
{
float:left;
width:182px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
}
#room
{

width:auto;

}
.droppable
{
width:80px;
}
#gallery
{
border:1px solid #3681CB;
}
#stuffname
{
border-bottom:1px solid #3681CB;
}
#tst
{
padding:0;
}
#topnavi{
	height: 150px;
	background-color: #3681CB;
	padding-top: 3px;
	padding-right: 0;
	padding-bottom: 5px;
	padding-left: 5px;
	height: 17px;
	margin-bottom: 15px;
	overflow: hidden;
}
#top_btn {
    float: right;
    margin: 0;
    padding: 0 15px 0 0;
}
</style>

<div id="topnavi">
    <h1>お客様一覧</h1>
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
	<div id="contents">
    <div style="font-size:16; font-weight:bold;">
	<?php 
	$user_id0=$get['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id0");	?>

          <?=$user_row['man_firstname']?> 様 ・ <?=$user_row['woman_firstname']?> 様
    </div>
    <div style="font-size:16; font-weight:bold;">
         <a href="users.php">お客様一覧</a> &raquo; 席次表
    </div>
		<div style="float:left; width:100%;">
    		        
        	<div id="room" style="float:left; width:<?=$room_width?>; ">
            	<div id="toptst" style="float:left; width:100%; ">     
					<?php
                    foreach($tblrows as $tblrow)
                    {
                        $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
						$align_term=0;
					   foreach($table_rows as $table_row)
							{
								
								if($table_row['display']==0 && $table_row['visibility']==0)
								{
									$align_term=1;
									break;	
								}
							}
						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");					
						if($ralign == 'C' && $align_term==1)
						{
							$num_none = $obj->GetNumRows("spssp_table_layout","user_id=".$user_id." and row_order=".$tblrow['row_order']." and display=0");
							if($num_none > 0)
							{
								$con_width = $row_width -((int)($num_none*178));
							}
							else
							{
								$con_width = $row_width;
							}
							
							$pos = 'margin:0 auto; width:'.$con_width.'px';
						}
						else if($ralign=='R' && $align_term==1)
						{
							$pos = 'float:right;';
						}
						else
						{
							$pos = 'float:left;';
							
						}
						
    
                    ?> 
                	<div class="rows" id="row_<?=$tblrow['row_order']?>"> 
                		<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />	
                		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$con_style;?>">
                    	<?php
                  		 	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
                  
							foreach($table_rows as $table_row)
							{
								$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
						
								if(isset($new_name_row) && $new_name_row['id'] !='')
								{
									$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
									$tblname = $tblname_row['name'];
									$tblname .= " &nbsp; <span style = 'display:none;'>".$tblname_row['id']." </span>";
								}
								else
								{
									$tblname = $table_row['name'];
								}
								
								if($table_row['visibility']==1 && $table_row['display']==1)
								{
		
									$disp = 'display:block;';
									$class = 'droppable';
		
								}
								else if($table_row['visibility']==0 && $table_row['display']==1)
								{
									$disp = 'visibility:hidden;';
									 $class = 'seat_droppable';
								}
								else if($table_row['display']==0 && $table_row['visibility']==0)
								{
									$disp = 'display:none;';
									$class = 'seat_droppable';
								}                    
                    		?>												
                        	<div class="tables" id="tid_<?=$table_row['id']?>" style="<?=$disp?>">
                                <p align="center" style="text-align:center" id="table_<?=$table_row['id']?>">
        
                                    <b><a href="#" style="cursor:default; text-decoration:none"><?=$tblname?></a> </b>
                                </p>
                            	<?php
                                //echo $disp;
                                $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");
                                foreach($seats as $seat)
                                {
                                ?>
                                    <div id="<?=$seat['id']?>" class="<?=$class?>" >
                                        <?php
                                        $key = $seat['id']."_input";
                                        if(isset($_SESSION['cart'][$key]) && $_SESSION['cart'][$key] != '')
                                        {
                                            $itemArray = explode("_", $_SESSION['cart'][$key]);
                                           
                                            $item = $itemArray[1];
                                            $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item);
                                            
											include("inc/main_dbcon.inc.php");
                                            $rspct = $obj->GetSingleData( "spssp_respect", "title"," id=".$item_info['respect_id']);
											include("inc/return_dbcon.inc.php");
                                            //echo $item_info['id'].'<br>';
                                            $edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                                            //echo '<p>'.$edited_nums.'</p>';
                                            
                                            if($edited_nums > 0)
                                            {
                                                $guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                                                $item_info['id']=$guest_editeds['id'];
                                                $item_info['sub_category_id']=$guest_editeds['sub_category_id'];
                                                $item_info['name']=$guest_editeds['name'];
                                                                                                        
                                            }
                                            
                                            
                                        ?>
                                            <ul id="abc" class="gallery ui-helper-reset ui-helper-clearfix" style="width:80px; height:30px;">
                                                <li class="ui-widget-content ui-corner-tr" id="item_<?=$item_info['id']?>" 
                                                    title="subul_<?=$item_info['sub_category_id']?>" style="width:80px; height:30px; padding:0; margin:0; border:0;">
														<?php
													$gname=$item_info['first_name']." ".$item_info['last_name']." ".$rspct;
													if($gname)
													{
														$name_length = mb_strlen($gname); 
															
															
															if($name_length > 12)
															{
																if($name_length >=12 && $name_length <= 14)
																{
																	$fsize = '86%';
																}
																else if($name_length >=15 && $name_length <= 17)
																{
																	$fsize = '78%';
																}
																else if($name_length >=18 && $name_length <= 20)
																{
																	$fsize = '72%';
																}
																else if($name_length >=21 && $name_length <= 23)
																{
																	$fsize = '66%';
																}
																else if($name_length >=24 && $name_length <= 26)
																{
																	$fsize = '60%';
																}
																else if($name_length >=27 && $name_length <= 29)
																{
																	$fsize = '50%';
																}
																else
																{
																	$fsize ='45%';
																}
															}
															else
															{
																$fsize = '92%';
															}
														}
															
													?>
                                                    <span style="border:0; width:80px;<?php if($gname) { ?> ;font-size:<?=$fsize?> <?php } ?>">
                                                        <?php echo $item_info['first_name']." ".$item_info['last_name']." ".$rspct;?> &nbsp; &nbsp;
                                                        <a href="javascript:editItem('subul_<?=$item_info['sub_category_id']?>',<?=$item_info['id'];?>,'<?=$item_info['name']?>','<?=$item_info['respect_id'].'_'.$rspct?>','<?=$item_info['sex']?>', '<?=$item_info['description']?>','<?=$seat['id']?>');" title="subul_<?=$item_info['sub_category_id']?>" style="display:none;">
                                                            <img src="img/edit.gif" border="0">
                                                        </a>
                                                    </span>
                                                    
                                                </li>
                                            </ul>
                                        <?php
                                        }
                                    ?>    
                                    </div>
                                <?php
                                }
                            	?>
                        	</div>
						  <?php
                           }
                          ?>                                   
                	  </div>
                	</div>
					<?php
                    }
                    ?>        
            	</div>
            
                <div style="width:100%; float:left; clear:both; text-align:left; "><br />
                    <form action="insert_default_plan.php?user_id=<?=(int)$_GET['user_id']?>&plan_id=<?=(int)$_GET['plan_id']?>" method="post" id="insert_plan" name="insert_plan">
                        <input type="button" value="プランを確認する" name="submit2" onclick="checkConfirm()"> &nbsp;
                        <input type="button" value="リセット" onClick="resetFormItems()">
                        <input type="button" value="戻る" onClick="goBack()">
                    </form>
                </div>    
        	</div>
        
        
    	</div>

	</div>
</div>
<div id="sidebar">
	<div id="stuffname"> 
    	<img src="img/common/nav_stuffname.gif" alt="TOP" width="148" height="30" />
        <?php
			$staff_name=$obj->GetSingleData("spssp_admin", "name"," id=".(int)$_SESSION['adminid']);
		?>
		<div id="stuffname_txt">
			<input type="text" size="15" readonly="readonly" value="<?=$staff_name;?>"  />
		</div>
	</div><br />

    	<ul class="nav">

        <li><a href="manage.php"><img src="img/common/nav_top.gif" alt="TOP" width="148" height="30" class=on /></a></li>

        <li><a href="users.php"><img src="img/common/nav_customer.gif" alt="お客様一覧" width="148" height="30" class=on /></a></li>
        </ul>
 <br />

	<div style="float:left; width:100%;">
    	<div id="guests_conatiner" style="float:left; width:100%;" >
        	<div class="demo ui-widget ui-helper-clearfix" id="tst"> 
                <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">       
                <?php
            
                    if($cats)
                    {
                        foreach($cats as $cat )
                        {
                            
                            $num_sub_cats = $obj->GetNumRows("spssp_guest_sub_category" , "category_id=".$cat['id']);
                    
                            if($num_sub_cats > 0 )
                            {
                                 $subcats = $obj->getRowsByQuery("select * from spssp_guest_sub_category where category_id=".$cat['id']);
                            ?>
                                <li id="cat_<?=$cat['id']?>" class="expandable">
                                    <div class="hitarea expandable-hitarea"></div>
                                    <span><strong><?=$cat['name']?></strong> 
                                        <!--<a class="editmenu" href="javascript:void(0)" title="cat_<?=$cat['id']?>" onclick="edit_guest_cat(<?=$cat['id']?>,'<?=$cat['name']?>')"><img src="img/edit.gif" border="0"></a>-->
                                    </span>
                                    <ul style="display: none;" id="sub_ul<?=$cat['id']?>">
                                    <?php
                                        foreach($subcats as $subcat)								
                                        {
                                            
                                            $num_guests = $obj->GetNumRows("spssp_guest" , "sub_category_id=".$subcat['id']);
                                            
                                            if($num_guests >0)
                                            {
                                                
                                                $guests = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and sub_category_id=".$subcat['id']." and id not in (select edit_item_id from spssp_guest where user_id=".(int)$user_id.") order by display_order DESC");
                                            ?>
                                                <li><div class="hitarea expandable-hitarea"></div><span><strong><?=$subcat['name']?></strong></span>
                                                    <span style="float:right;"><!--<a href="#" onClick="addNewItem('subul_<?=$subcat['id']?>',<?=$user_id?>);">+</a>--></span>
                                                    <ul style="display: none;" id="subul_<?=$subcat['id']?>">
                                                    <?php 
                                                        foreach($guests as $guest)
                                                        {
                                                          
														    if(in_array($guest['id'],$itemids))
                                                            {
                                                                $class = "dragfalse";
                                                                $src = "img/icon01.jpg";
                                                                $style = "style = 'display:none'";
                                                            }
                                                            else
                                                            {
                                                                $class = "ui-widget-content ui-corner-tr";
                                                                $src = "img/icon02.jpg";
                                                                $style = "style = 'display:block'";
                                                            }
															
															include("inc/main_dbcon.inc.php");
                                                            $rsp = $obj->GetSingleData( "spssp_respect", "title"," id=".$guest['respect_id']);
															include("inc/return_dbcon.inc.php");
                                                            
                                                            $edited_num = $obj->GetNumRows("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".(int)$user_id);
                                                            //echo '<h1>'.$edited_num.'</h1>';
                                                            if($edited_num > 0)
                                                            {
                                                                $guest_edited = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".$user_id);
                                                                $guest['id']=$guest_edited['id'];
                                                                $guest['sub_category_id']=$guest_edited['sub_category_id'];
                                                                $guest['name']=$guest_edited['name'];																													
                                                            }
															$gname = $guest['first_name']." ".$guest['last_name']." ".$rsp;
															$name_length = strlen($gname); 
															
															if($name_length > 12)
															{
																if($name_length >=12 && $name_length <= 14)
																{
																	$fsize = '86%';
																}
																else if($name_length >=15 && $name_length <= 17)
																{
																	$fsize = '78%';
																}
																else if($name_length >=18 && $name_length <= 20)
																{
																	$fsize = '72%';
																}
																else if($name_length >=21 && $name_length <= 23)
																{
																	$fsize = '66%';
																}
																else if($name_length >=24 && $name_length <= 26)
																{
																	$fsize = '60%';
																}
																else if($name_length >=27 && $name_length <= 29)
																{
																	$fsize = '50%';
																}
																else
																{
																	$fsize ='45%';
																}
															}
															else
															{
																$fsize = '92%';
															}
                                                        
                                                    ?> 
                                                        <li class="<?=$class?>" id="item_<?=$guest['id']?>"  title="subul_<?=$guest['sub_category_id']?>" style="height:25px; white-space:nowrap; padding-left:0;">
                                                        <div id="icon_<?=$guest['id']?>" style="width:20px;float:left; "><img src="<?=$src?>" border="0" id="icon<?=$guest['id']?>" /></div>
                                                    
                                                        <span style="font-size:<?=$fsize?>">
                                                            <?=$guest['first_name']." ".$guest['last_name']." ".$rsp ;?> &nbsp;&nbsp;
                                                             <a href="javascript:editItem('subul_<?=$guest['sub_category_id']?>',<?=$guest['id']?>,'<?=$guest['name']?>','<?=$guest['respect_id'].'_'.$rsp?>','<?=$guest['sex']?>','<?=$guest['description']?>','tst');" title="subul_<?=$guest['sub_category_id']?>" style="display:none;">
                                                                <img src="img/edit.gif" border="0">
                                                            </a>
                                                        
                                                        </span>
                                                                                                            
                                                        </li>
                                                    <?php		
                                                        }
                                                    ?>
                                            <?php
                                                echo "</ul>";
                                            }
                                            else
                                            {
                                            ?>
                                            <li class="dragfalse" id="item_<?=$subcat['id']?>"  title="sub_ul<?=$cat['id']?>">
                                            <?=$subcat['name']?>
                                               
                                            </li>
                                            <?php
                                            }
                                        }
                                        echo "</ul>";
                                    ?>
                            <?php					
                            }
                            else
                            {
                            ?>
                            
                                <li  id="item_<?=$cat['id']?>"  title="gallery">
                                <div class="hitarea expandable-hitarea"></div><span><strong><?=$cat['name']?></strong></span>
                                    <div class="test" style="display:none;">
                                        
                                       
                                     </div>
                                </li>
                            <?php
                            }
                        }
                    }			
                ?>
                </ul>
            </div>
        </div>
    </div>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
</div>

<?php
	//include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>

