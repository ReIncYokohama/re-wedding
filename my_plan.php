<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);
//echo $_SESSION[userid];exit;
include_once("inc/plan.new_header.php");
/*echo "<pre>";
print_r($_SESSION['cats']);
print_r($_SESSION['subcats']);
exit;*/
$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id=0 and default_cat_id=0');
	

	$plan_id = $get['user_plan_id'];

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);
	
	
	$room_rows = $plan_row['row_number'];
	
	$row_width = (int)(740/$room_rows);
	$row_width = $row_width-6;
	
	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;
	
	$room_tables = $plan_row['column_number'];
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");
	//print_r($table_rows);
	$itemids = array();
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
	/*echo "<pre>";
	print_r($_SESSION['cart']);exit;*/
	include("admin/inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow("dev2_main.spssp_respect");
	include("admin/inc/return_dbcon.inc.php");
?>
<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='javascript:void(0);' onclick = 'choose_again()'>Back</a></li>  <li class='active'> <h2> > Make Your Plan - <span style='font-size:12px'> Drag Guests from left menu in to chairs group by tables of room </span></h2></li>   </ul>");
});
</script>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
	$("ul#menu li").removeClass();
	$("ul#menu li:nth-child(3)").addClass('active');
	//$("ul#menu li:nth-child(2) a").attr("href",'registration_2.php?id=<?=$get['id']?>');
	$("div#toptst :first").css("border-left","solid 1px #666666");
	$("div#toptst :last").css("display","none");
});
function checkConfirm()
{
	var conf;
	conf = confirm('修正内容を保存しても宜しいですか？');
	if(conf)
	{
		$.post('check_default_guests.php', function(data){
			if(data != '')
			{
				var dfltGuest = confirm("You Have Defualt Guest that you don't make any change. Wanna Change It???");
				if(dfltGuest)
				{
					var width = $(data).width();
					var height = $(data).height();
	
					var bgcl = $(data).css("background-color");
					var spbg = $(data+" ul li  span").css("background-color");
					var clr = $(data+" ul li ").css("color");
					
					$(data).css("background-color","#FF0000");
					$(data+" ul li ").css("color","#FFFFFF");
					$(data+" ul li ").css("font-weight","bold");
				
					$(data+" ul li ").removeClass();
					$(data+" ul li  span").css("background-color","#FF0000");
					$(data+" ul li  span a").hide();
					
					//$(data).animate({ 'background-color': bgcl }, 2600);
					
					
					
					
					$(data).animate({width: width+'px'}, 1500, function(){
						//$(data).fadeTo("slow", 0.33);

						$(data).css("background-color",bgcl);
						//$(data).animate({ 'background-color': bgcl }, 2600);

						//$("body").css('z-index':100);
						//$(data).css('z-index':200);
						//$(data).css('background-color', "#FF0000").animate({'background-color': bgcl}, 1500);
						$(data+" ul li").addClass("ui-widget-content");						
						$(data+" ul li  span").css("background-color",spbg);
						
						$(data+" ul li ").css("color",clr);
						$(data+" ul li ").css("font-weight","normal");
						$(data+" ul li  span a").fadeIn(1500);
						$(data).fadeIn(1500);
					});
					
					
					//alert(bgcl);
					//$(data).css('width', parseInt(width/2));
					
					//$(data).animate({width: width},{duration: 1000, specialEasing: { width: 'linear', height: 'easeOutBounce' }});
				/*	$(data).animate({height: height+'px'}, 500);
					$(data).animate({width: 0+'px'}, 500, function(){
						$(data).css('z-index', i);
						$(data).animate({height: height+'px'},500);
						$(data).animate({width: width+'px'},500);
					});*/
					//$(data).fadeTo('fast',0.2)

				}
				else
				{
					conf = confirm(" You Can not make any change to this plan for the next time.修正内容を保存しても宜しいですか？");
					if(conf)
					{
						conf = confirm("修正内容を保存しても宜しいですか？");
						if(conf)
						{
							$("#insert_plan").submit();
						}
					}
				}
				
			}
			else
			{
				conf = confirm(" You Can not make any change to this plan for the next time.修正内容を保存しても宜しいですか？");
				if(conf)
				{
					conf = confirm("修正内容を保存しても宜しいですか？");
					if(conf)
					{
						$("#insert_plan").submit();
					}
				}
			}
		});
	}
	
}



</script>

<div style="float:left; width:100%;">
<div id="guests_conatiner">
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
                    	<li id="cat_<?=$cat['id']?>">
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
										/*$guests = $obj->getRowsByQuery("(SELECT id, sub_category_id, name, respect_id FROM spssp_default_guest  where sub_category_id=".$subcat['id'].") union all (select id, sub_category_id, name, respect_id from spssp_guest where sub_category_id=".$subcat['id']." 
										and user_id=".(int)$_SESSION['userid'].")");*/
										//print_r($guests);exit;
										$guests = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".(int)$_SESSION['userid']." and sub_category_id=".$subcat['id']." and id not in (select edit_item_id from spssp_guest where user_id=".(int)$_SESSION['userid'].") order by display_order DESC");
									?>
                                    	<li><div class="hitarea expandable-hitarea"></div><span><strong><?=$subcat['name']?></strong></span>
                                        	<span style="float:right;"><a href="#" onClick="addNewItem('subul_<?=$subcat['id']?>',<?=$_SESSION['userid']?>);">+</a></span>
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
													include("admin/inc/main_dbcon.inc.php");
													$rsp = $obj->GetSingleData("dev2_main.spssp_respect", "title"," id=".$guest['respect_id']);
													include("admin/inc/return_dbcon.inc.php");
													
													$edited_num = $obj->GetNumRows("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".(int)$_SESSION['userid']);
													//echo '<h1>'.$edited_num.'</h1>';
													if($edited_num > 0)
													{
														$guest_edited = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".(int)$_SESSION['userid']);
														$guest['id']=$guest_edited['id'];
														$guest['sub_category_id']=$guest_edited['sub_category_id'];
														$guest['name']=$guest_edited['name'];														
													}
												
											?> 
                                            	<li class="<?=$class?>" id="item_<?=$guest['id']?>"  title="subul_<?=$guest['sub_category_id']?>">
                                                <div id="icon_<?=$guest['id']?>" style="width:20px;float:left; "><img src="<?=$src?>" border="0" id="icon<?=$guest['id']?>"/></div>
                                            
												<span>
													<?=$guest['name']." ".$rsp?> &nbsp;&nbsp;
                                                     <a href="javascript:editItem('subul_<?=$guest['sub_category_id']?>',<?=$guest['id']?>,'<?=$guest['name']?>','<?=$guest['respect_id'].'_'.$rsp?>','<?=$guest['sex']?>','<?=$guest['description']?>','tst');" title="subul_<?=$guest['sub_category_id']?>" <?=$style?>>
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
    
    
    <div id="room" style="float:left; width:740px;">
		<div id="toptst">      
            <div class="rows" style="width:<?=$row_width?>px"> 	
                <?php
                $i=1;
                foreach($table_rows as $table_row)
                {
                	$user_edited_tblname = $obj->GetSingleData("spssp_user_table", "name"," default_table_id=".$table_row['id']." and user_id=".(int)$_SESSION['userid']); 
					if(isset($user_edited_tblname) && $user_edited_tblname !='')
					{
						$tblname = $user_edited_tblname;
					}
					else
					{
						$tblname = $table_row['name'];
					}
                ?>
                    <div class="tables">
                        <p align="center" style="text-align:center" id="table_<?=$table_row['id']?>">
                        	<b><a href="javascript:void(0);" onclick="edit_table_name(<?=$table_row['id']?>,'<?=$tblname?>');" > <?=$tblname?> </a></b>
                        </p>
                        <?php
                            $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['id']." order by id asc limit 0,$room_seats");
                            foreach($seats as $seat)
                            {
                            ?>
                                <div id="<?=$seat['id']?>" class="droppable" style="width:<?=$table_width?>px">
                                    <?php
                                    $key = $seat['id']."_input";
                                    if(isset($_SESSION['cart'][$key]) && $_SESSION['cart'][$key] != '')
                                    {
                                        $itemArray = explode("_", $_SESSION['cart'][$key]);
                                       
                                        $item = $itemArray[1];
                                        $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item);
                                        include("admin/inc/main_dbcon.inc.php");
                                        $rspct = $obj->GetSingleData("dev2_main.spssp_respect", "title"," id=".$item_info['respect_id']);
										
										include("admin/inc/return_dbcon.inc.php");
										//echo $item_info['id'].'<br>';
										$edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$_SESSION['userid']);
										//echo '<p>'.$edited_nums.'</p>';
										
										if($edited_nums > 0)
										{
											$guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$_SESSION['userid']);
											$item_info['id']=$guest_editeds['id'];
											$item_info['sub_category_id']=$guest_editeds['sub_category_id'];
											$item_info['name']=$guest_editeds['name'];
																									
										}
										
										
                                        ?>
                                        <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
                                            <li class="ui-widget-content ui-corner-tr" id="item_<?=$item_info['id']?>" 
                                                title="subul_<?=$item_info['sub_category_id']?>">
                                                <span style="border:0">
                                                    <?php echo $item_info['name']." ".$rspct;?> &nbsp; &nbsp;
                                                    <a href="javascript:editItem('subul_<?=$item_info['sub_category_id']?>',<?=$item_info['id'];?>,'<?=$item_info['name']?>','<?=$item_info['respect_id'].'_'.$rspct?>','<?=$item_info['sex']?>', '<?=$item_info['description']?>','<?=$seat['id']?>');" title="subul_<?=$item_info['sub_category_id']?>">
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
                    if($i % $room_tables == 0 && $i !=0)
                    {
                        echo "</div> <div class='rows' style='width:".$row_width."px'>";
                    }
                $i++;
                }
    
                ?>
            
            </div>        
		</div>
        
        <div style="width:100%; text-align:right; "><br />
            <form action="insert_default_plan.php?id=<?=$_GET['id']?>&plan_id=<?=(int)$_GET['user_plan_id']?>" method="post" id="insert_plan" name="insert_plan">
                <input type="button" value="Confirm Plan" name="submit2" onclick="checkConfirm()"> &nbsp;
                <input type="button" value="Reset" onClick="resetFormItems()">
                <input type="button" value="Back" onClick="goBack()">
            </form>
        </div>
        
        <div class="demo">
<div id="dialog-form" title="新しいユーザーを追加します。">
	
	<form action="" method="post" id="colorSerForm" name="colorSerForm">
	<fieldset>

        <input type="hidden" name="ulId" value="" id="ulId" />
        <input type="hidden" name="edit_li_id" value="" id="edit_li_id" />
        <input type="hidden" name="userid" value="" id="userid" />
        <input type="hidden" name="daycount" value="" id="daycount" />
        <table align="center" border="0">
        	<tr>
            	<td> Name: </td> <td><input type="text" id="name" name="name" /></td>
            </tr>

            <tr>
                 <td> Respect: </td> 
                <td>
                    <select id="respect_id" name="respect_id">
                        <option value="">--------</option>
                        <?php
                            foreach($respects as $rsp)
                            {
                                echo "<option value='".$rsp['id']."_".$rsp['title']."'>".$rsp['title']."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
				<td>Sex</td>
                <td>
                	<select id="sex" name="sex">
                    	<option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Description</td>
                <td>
                	<textarea id="description" name="description"></textarea>
                </td>
            </tr>
        </table>          
	</fieldset>
	</form>
</div>

<div id="dialog-form_edit" title="ユーザー情報">
	
	<form action="" method="post" id="colorSerForm" name="colorSerForm">
	<fieldset>

        <input type="hidden" name="ulId_edit" value="" id="ulId_edit" />
        <input type="hidden" name="edit_li_id" value="" id="edit_li_id" />
        <input type="hidden" name="userid_edit" value="" id="userid" />
        <input type="hidden" name="divid" value="" id="divid" />
        
        <table align="center" border="0">

        	<tr>
            	<td> Name: </td> <td><input type="text" id="name_edit" name="name" /></td>
            </tr>

            <tr>
                 <td> Respect: </td> 
                <td>
                    <select id="respect_id_edit" name="respect_id_edit">
                        <option value="">--------</option>
                        <?php
                            foreach($respects as $rsp)
                            {
                                echo "<option value='".$rsp['id']."_".$rsp['title']."'>".$rsp['title']."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
				<td>Sex</td>
                <td>
                	<select id="sex_edit" name="sex_edit">
                    	<option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Description</td>
                <td>
                	<textarea id="description_edit" name="description"></textarea>
                </td>
            </tr>
        </table> 
	</fieldset>
	</form>
</div>



<!--<div id="cat_edit" title="ユーザー情報">	
	<form action="" method="post" id="cat_edit_form" name="cat_edit_form">
	<fieldset>

        <input type="hidden" name="cat_id" value="" id="cat_id" />

        <input type="hidden" name="catli_id" value="" id="catli_id" />
        
        <table align="center" border="0">

        	<tr>
            	<td> Name: </td> <td><input type="text" id="cat_names" name="cat_names" /></td>
            </tr>


            <tr>
            	<td>Description</td>
                <td>
                	<textarea id="cat_description" name="cat_description"></textarea>
                </td>
            </tr>
        </table> 
	</fieldset>
	</form>
</div>-->

<div id="table_edit" title="ユーザー情報">	
	<form action="" method="post" id="table_edit_form" name="table_edit_form">
	<fieldset>

        <input type="hidden" name="table_id" value="" id="table_id" />

   
        
        <table align="center" border="0">

        	<tr>
            	<td> Name: </td> <td><input type="text" id="table_name" name="table_name" /></td>
            </tr>


            <tr>
            	<td>Description</td>
                <td>
                	<textarea id="table_desc" name="table_desc"></textarea>
                </td>
            </tr>
        </table> 
	</fieldset>
	</form>
</div>



</div>

</div>
    
    
</div>
</div>
<?php

include("inc/new_footer.php");
?>
