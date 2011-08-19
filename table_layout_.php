<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	include_once("inc/new.header.inc.php");
	
	
	$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$user_id);	
	
	if($_POST['user_layout_title']="user_layout_title")
	{
		unset($_POST['user_layout_title']);
		unset($_POST['submit']);
		$obj->UpdateData("spssp_plan",$_POST," user_id=".$user_id);
	}
	
?>

<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />

<script>
$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(1)").addClass("active");

$(".rows").filter(":first").css("border-top","1px solid #666666");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
function checkGuest(tid,cid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
		if(parseInt(data) > 0)
		{
			$("#"+cid).attr('checked','checked');
			alert('There are Guests on this Table. Please remove them first.');
		}
	});
}
function validForm(num,id)
{
	
	var tableId="tableId_"+num;
	
	var name  = document.getElementById(tableId).value;
	
	var showtableid="table_"+id;	

	var flag = true;
	if(!name)
	{
		 alert("「卓名を入力してください」");
		 document.getElementById(tableId).focus();
		 return false;
	}
	else
	{
		$.post('ajax/plan_table_name_update.php',{'name':name,'id':id}, function(data){
			if(data)
			{
				
				$("#"+showtableid+" b").html(data);
				alert("更新されました");
				//$("#msg_rpt_err").hide("slow");
			}
			else
			{
				//$("#msg_rpt_err").show("slow");
				alert("更新されていません");
				//$("#msg_rpt").hide("slow");
			}
		});
	
	}	
}
function user_layout_title_input_show(id) 
	{
		$("#"+id).fadeOut();
		$("#input_user_layoutname").fadeIn(500);
		
	}	
</script>
<style>

	.tables
	{
		height:31px;
		width: 31px;
		float:left;

		background-image:url(img/circle_small.jpg);
	
	}

	.tables
	{

		margin:5px 10px;
	
	}
	.tables p
	{
		
		margin-top:5px;
		margin-left:5px;
		
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;
	
	}
</style>

<div id="step_bt_area">
	  <div class="step_bt"><img src="img/step_head_bt01_on.jpg" width="155" height="60" border="0"/></div>
	  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
	  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="155" height="60" border="0" class="on" /></a></div>
	  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
	  <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="155" height="60" border="0" class="on" /></a></div>
	  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
	  <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="155" height="60" border="0" class="on" /></a></div>
	  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
	  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="155" height="60" border="0" class="on" /></a></div>
	  <div class="clear"></div>
</div>



<div id="main_contents">
  <div class="title_bar">
    <div class="title_bar_txt_L">テーブルレイアウトをご覧ください。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
  <div class="cont_area">
    <div class="info_box">
      <div class="info_area_L">
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td>
            <td width="378">テーブルレイアウトは右の形になります。<br />
              一卓の最大人数　8名まで。</td>
          </tr>
        </table>
        <br />
       <?php
		$user_tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".$user_id." and visibility=1 and display=1");
		$permission_table_edit = $obj->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
	   
	   if($permission_table_edit['rename_table']) {?>
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td>
            <td width="378">テーブル名は変更が可能です。<br />
              変更する場合は、それぞれ入力してください。</td>
          </tr>
      </table>

        <?php
		
		?>
        <?php
	   }
		if(isset($_GET['msg']) && $_GET['msg'] !='')
		{
			$obj->GetSuccessMsg((int)$_GET['msg']);
		}
		else if(isset($_GET['err']) && $_GET['err'] !='')
		{
			$obj->GetErrorMsg((int)$_GET['err']);
		}
	
	  ?>
		<div id='msg_rpt' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:green;font-weight:bold;font-size:13px;'>
		Table name succesfully updated.
		</div>
		<div id='msg_rpt_err' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:red;font-weight:bold;font-size:13px;'>
		Table name could not updated.
		</div>
		<? if($permission_table_edit['rename_table']) {?>
		<table width="410" border="0" cellspacing="0" cellpadding="5">
         <tr>
		  <?php 
		  $k=1;
		  	
			foreach($user_tables as $user_table_row)
			{
				
				if($user_table_row['table_id'])
				{
				 	//$default_table_name = $obj->GetSingleData("spssp_default_plan_table", "name"," id=".$user_table_row['table_id']);
				  if($permission_table_edit==1)
				  {?>
				  <form name="tableEdit_form_<?=$k?>" method="post" action="table_layout.php">
				 <?php }
			?>
		  
           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap"><input name="tableName_<?=$k?>" type="text" id="tableId_<?=$k?>" value="<?=$user_table_row['name']?>" size="10" /></td>
          <?php
		   if($permission_table_edit==1)
				  {?>
				  <td><input type="button" name="edit" value="保存" onclick="validForm(<?=$k?>,<?=$user_table_row['id']?>);"></td>
				  </form>
				 <?php }
		  
		  
		  	if($k%2==0)
			{
				echo "</tr><tr>";
			}
		  
		  ?>
		  
		  
         <?php $k++;}} ?>
        </tr>
		</table>
		<? } ?>
		<!--<table width="410" border="0" cellspacing="0" cellpadding="5">
         <tr>
		  <?php 
		  /*$k=1;
		  	
			foreach($user_tables as $user_table_row)
			{
				$optionvalue='';
				foreach($user_tables as $key => $user_table_row1)
				{
				    $keyvalue= $key+1;
				    $selected =($k == $keyvalue)?"selected":"";
					$optionvalue .='<option '.$selected.' value="'.$user_table_row1['name'].'">'.$user_table_row1['name'].'</option>';
				}
				
				if($user_table_row['table_id'])
				{
				 	//$default_table_name = $obj->GetSingleData("spssp_default_plan_table", "name"," id=".$user_table_row['table_id']);
				  if($permission_table_edit==1)
				  {?>
				  <form name="tableEdit_form_<?=$k?>" method="post" action="table_layout.php">
				 <?php }
			?>
		  
           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap">
		   <select name="tableName_<?=$k?>" id="tableId_<?=$k?>" style="width:100px;" onchange="validForm(<?=$k?>,<?=$user_table_row['id']?>);">
		   <?=$optionvalue?>
		   </select>
		   
		   </td>
          <?php
		   if($permission_table_edit==1)
				  {?>
				  <td><input type="button" name="edit" value="保存" onclick="validForm(<?=$k?>,<?=$user_table_row['id']?>);"></td>
				  </form>
				 <?php }
		  
		  
		  	if($k%2==0)
			{
				echo "</tr><tr>";
			}
		  
		  ?>
		  
		  
         <?php $k++;} */?>
        </tr>
		</table>-->
		
      </div>
      <div class="info_area_R" style="">■　テーブルのレイアウト<br />
      		<div style="width:100%; float:left; text-align:center; ">
       			
				
				
       		<?php
            	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
				$num_tables = $obj->getSingleData("spssp_plan", "column_number"," user_id= $user_id");
				$rw_width = (int)($num_tables* 51);
			?>
			<div>
	<?php
		$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
		$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
		if($layoutname!="")
		{
			echo "<div id='user_layoutname' onclick='user_layout_title_input_show(\"user_layoutname\");' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$layoutname."</div>";
		}
		elseif($default_layout_title!="")
		{
			echo "<div id='default_layout_title' onclick='user_layout_title_input_show(\"default_layout_title\");' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
		}
		else
		{
			echo "<p id='img_default_layout_title' onclick='user_layout_title_input_show(\"img_default_layout_title\");' style='text-align:center'><img src='admin/img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
		}
		
		echo "<div id='input_user_layoutname' style='display:none;'>
		<form action='table_layout.php' method='post'>
		<input type='hidden' name='user_layout_title' value='user_layout_title'>
		<input type='text' name='layoutname' value='".$layoutname."'>
		<input type='submit' name='submit' value='保存'>
		</form>
		</div>";
		?>
			</div>
        	<div align="center" style="width:<?=$rw_width?>px; margin:0 auto;">
            	<?php
				
				foreach($tblrows as $tblrow)
				{
					
						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");
						$num_hidden_table = $obj->GetNumRows("spssp_table_layout","user_id = $user_id and display = 0 and row_order=".$tblrow['row_order']);
						if($ralign == 'L')
						{
							$pos = 'float:left;';
						}
						else if($ralign=='R')
						{
							$pos = 'float:right;';
						}
						else
						{
							$wd = $rw_width - ($num_hidden_table*51);
							$pos = 'margin:0 auto; width:'.$wd.'px';
						}
										
				?>
    			<div class="rows" style="float:left;width:100%" id="row_<?=$tblrow['row_order']?>"> 
            	<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />
            	
            		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$pos;?>">
				<?php
                $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
			
				
                    foreach($table_rows as $table_row)
                    {
                        $new_name_row = $obj->GetSingleRow("spssp_user_table", " user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
						
							
                            $tblname='';
							//print_r($new_name_row);//exit;
                            if($table_row['name']!='')
                            {
$tblname = $table_row['name'];
					//			echo'<pre>';
				//print_r($tblname_row);
                            }
                            elseif(is_array($new_name_row) && $new_name_row['id'] !='')
                            {
                              
							    $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
                                
								$tblname = $tblname_row['name'];
                            }
							
							
                            
                            if($table_row['visibility']==1 && $table_row['display']==1)
                            {
    
                                $disp = 'display:block;';
    
                            }
                            else if($table_row['visibility']==0 && $table_row['display']==1)
                            {
                                $disp = 'visibility:hidden;';
                            }
                            else if($table_row['display']==0 && $table_row['visibility']==0)
                            {
                                $disp = 'display:none;';
                            }
                    ?>
                    <div class="tables" style="<?=$disp?>">
                        <p align="center" style="text-align:center;" id="table_<?=$table_row['id']?>">
            
                            <b> <?=mb_substr ($tblname, 0,1,'UTF-8');?></b>  &nbsp; 
                       
                        </p>
                       
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

            
        </div>  
        
	  
	  </div>
    </div> <div class="clear"></div>
</div>





<?php
include("inc/new.footer.inc.php");
?>