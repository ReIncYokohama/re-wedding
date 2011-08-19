<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$roomid = (int)$get['room_id'];
	if((int)$get['plan_id'] > 0)
	{
		unset($_SESSION['cart']);
	}
	
	$table='spssp_default_plan';
	$where = " room_id=".$roomid;
	$data_per_page=10;
	$current_page=(int)$get['page'];
	$redirect_url = 'plans.php?room_id='.$roomid;	
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$get['id'];
		mysql_query($sql);
		
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{		
		$id = $get['id'];
		$move = $get['move'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	
	
	$query_string="SELECT t.*, rm.name as room FROM $table t left outer join spssp_room rm on t.room_id=rm.id where t.room_id=".$roomid." ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$data_rows = $obj->getRowsByQuery($query_string);
	
	$room_row = $obj->GetSingleRow("spssp_room", " id=".$roomid);
	
?>
<style>

	.tables
	{
		height:31px;
		width: 31px;
		float:left;
		margin:5px 5px;
		background-image:url(img/circle_small.jpg);
	
	}

	.tables p
	{
		margin-top:5px;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;
	
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
    
    $('#row_number').keyup(function(){        
		var r=isInteger("row_number");		
    });
	$('#column_number').keyup(function(){        
		var r=isInteger("column_number");		
    });
	$('#seat_number').keyup(function(){        
		var r=isInteger("seat_number");		
    });
	
	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
	
});
function isInteger(id){
 var i;
 var s=$("#"+id).val();
    for (i = 0; i < s.length; i++){  
        // Check that current character is number.
        var c = s.charAt(i);
       if(i==0&&c==0)
	   {
	   		var msg="0の入力はできません。";
			$('#'+id).attr("value","");
			alert(msg);
	   }
	    if (((c < "0") || (c > "9")))
		{
			var msg="数字のみの入力はできません。";
			$('#'+id).attr("value","");
			 alert(msg);
		}
    }
    // All characters are numbers.
    return true;
}
	function checkGuests(urls)
	{
		$.post("check_guests.php",  function(data){
			var numrow = parseInt(data);
     		if(numrow <= 0)
			{
				var conf = confirm("You Don't have any Default Guest.Wanna Enter Guest Information");
				if(conf)
				{
					window.location = "guest_categories.php";
				}
			}
			else
			{
				window.location = urls;
			}
   		});
	}
	function edit_plan(plan_id, page, room_id)
	{
		$.post('set_session1.php', {'plan_id': plan_id,'page':page,'room_id':room_id}, function(data) {
			var urls = "default_plan.php?plan_id="+plan_id+"&room_id="+room_id+"&page="+page;
			window.location = urls;								
		});	
	}
	function create_new_plan()
	{
		
		var disp=$("#plan_new").css('display');

		if(disp != 'block')
		{
			$("#new_anchor").html("キャンセル");
		}
		else
		{
			$("#new_anchor").html("<img src='img/common/btn_new.gif' width='82' height='22' />");
		}
		
		
		$("#plan_new").toggle("slow");
		
	}
	
	
	
	function validForm()
	{
		var name  = document.getElementById('name').value;
		var room_id  = document.getElementById('room_id').value;
		var row_number  = parseInt(document.getElementById('row_number').value);
		var column_number  = parseInt(document.getElementById('column_number').value);
		var seat_number  = parseInt(document.getElementById('seat_number').value);
		
		//var total = row_number * column_number * seat_number;
		
		var max_rows = parseInt(document.getElementById('max_rows').value);
		var max_columns = parseInt(document.getElementById('max_columns').value);
		var max_seats = parseInt(document.getElementById('max_seats').value);
		
		var flag = true;
		if(!name)
		{
			 alert("披露宴会場名が未入力です");
			 document.getElementById('name').focus();
			 return false;
			
		}
	
		if(!room_id)
		{
			 alert("Roomidが未入力です");
			 document.getElementById('room_id').focus();
			return false;
		}
		if(!row_number)
		{
			 alert("列が未入力です");
			 document.getElementById('row_number').focus();
			return false;
		}
		if(row_number > max_rows)
		{
			alert('会場の最大卓数 横の値を超えています。会場の最大卓数 横以下の値を入力してください。');
			document.getElementById('row_number').focus();
			return false;
		}
		
		if(!column_number)
		{
			 alert("縦が未入力です");
			 document.getElementById('column_number').focus();
			return false;
		}
		if(column_number > max_columns)
		{
			alert('会場の最大卓数 縦の値を超えています。会場の最大卓数 縦以下の値を入力してください。');
			document.getElementById('column_number').focus();	
			return false;
		}
		
		if(!seat_number)
		{
			 alert("一卓人数は数字で入力してください");
			 document.getElementById('seat_number').focus();
			return false;
		}
		if( seat_number > max_seats)
		{
			alert('Seat Number is Larger than Room Allows in each table');
			document.getElementById('seat_number').focus();
			return false;
		}
		
		document.plan_form.submit();
			
	}
$(function(){
/*var row_width =$(".rows").width();

var table_width = $(".tables").width();
var table_width = table_width;
$(".preview").each(function(){

var prv_id = "#"+$(this).attr('id');
	
	/*if($(this).css('display')!= 'none')
	{*/
		/*$(prv_id+" .rows").each(function(){
			var count_none = 0;
		
			var rowid = $(this).attr('id');	
			var row_id_arr = rowid.split("_");
			var center_id = "#rowcenter_"+row_id_arr[1];
			
			var ralign = $(prv_id+" #"+rowid+" input"+center_id).val();
			
			
			if(ralign <= 0)
			{
				 count_none = 0;
				
				$(prv_id+" #"+rowid+" .tables").each(function(){	
					//alert(prv_id+" #"+rowid+" .tables"+'___'+$(this).css('display'));	
					
					if($(this).css('display') == 'none')
					{
						count_none++;
					}
					
				});
				alert(count_none+"prv"+prv_id+" row #"+rowid);
				if(count_none > 0)
				{
					//alert(row_width+'RW'+table_width+'TW'+count_none+'CNT');
					var row_con_width = row_width -(table_width*count_none);
					
					$("#"+rowid+" .row_conatiner").css({'width':row_con_width+'px','margin':'0 auto'});
					count_none = 0;
					
				}
							
			}
		
		});
	//}
});*/
});

function preview_plan(plan_id)
	{
		/*$.post('../ajax/room_preview.php',{'plan_id':plan_id}, function(data){
			$("#table_preview div").fadeOut(100);
			$("#table_preview div").html(data);
			$("#table_preview div").fadeIn(500);
		});*/
		var prv_id = "prv_"+plan_id;
		//$("#"+prv_id).fadeIn(500);
		/*$(".preview").each(function(){
			$(this).fadeOut();
			if($(this).attr('id') == prv_id)
			{
				$(this).fadeIn(100);
				
			}
		});*/
		$(".preview").hide();
		$("#"+prv_id).fadeIn();
			
		
	}

</script>


<div id="topnavi">
    <h1>席次表・席札　編集　会場別卓レイアウト設定</h1>
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents"> 
	 <h2>       	
            	 <a href="rooms.php">披露宴会場登録</a> &raquo; 卓レイアウト設定
        </h2>
    	<div class="box_table">
			<div class="bottom_line_box">
	  			<p class="txt3">
                	会場名：<?=$room_row['name']?>　　最大卓数：横<?=$room_row['max_rows']?>列 × 縦<?=$room_row['max_columns']?>段　　一卓人数：<?=$room_row['max_seats']?>名
                </p>
            </div>
            
            <?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				echo "<script>
			alert('".$obj->GetErrorMsgNew((int)$_GET['err'])."');
			</script>";
				

			}
			else if(isset($_GET['msg']) && $_GET['msg']!='')
			{
				
				echo "<script>
					alert('".$obj->GetSuccessMsgNew((int)$_GET['msg'])."');
				</script>";
				

			}
			?>
            
      		<p class="txt3">卓レイアウト設定：
            	<a href="javascript:void(0);" onclick="create_new_plan()" id="new_anchor"><img src="img/common/btn_new.gif" width="82" height="22"  /></a>
            </p>
            
            <div id="plan_new" style="display:none">
            	<p class="txt3">
                    <form action="plan_new.php?room_id=<?=(int)$_GET['room_id']?>" method="post" name="plan_form">
                    	<input type="hidden" name="room_id" id="room_id" value="<?=(int)$room_row['id']?>" />
                        卓レイアウト名：<label for="textfield"></label>   <input name="name" type="text" id="name" size="10" />
                　		最大卓数：横  <label for="textfield2"></label>  
                		<input name="row_number" type="text" id="row_number" maxlength="3" size="1" value="<?=$room_row['max_rows']?>" />   列×縦
                        <label for="textfield3"></label> 
                        <input name="column_number" type="text" id="column_number" maxlength="3" size="1" value="<?=$room_row['max_columns']?>" />  段　
                        一卓人数：<label for="textfield4"></label>  
                        <input name="seat_number" type="text" maxlength="2" id="seat_number" size="1" value="<?=$room_row['max_seats']?>" />    人
                　　	<a href="#" onclick="validForm();">
                            <img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22"  />
                        </a>
                         	<input type="hidden"  id="max_columns" value="<?=$room_row['max_columns']?>" />
                            <input type="hidden"  id="max_seats" value="<?=$room_row['max_seats']?>" />
                            <input type="hidden"  id="max_rows" value="<?=$room_row['max_rows']?>" />
                     </form>
         		</p>
                <p></p>
            </div>
		</div>
        <div class="box_table">
        	<div class="page_next"><?=$pageination?></div>
            
        	<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td>卓レイアウト名</td>
                        <td>卓数</td>
                        <td>一卓人数</td>
                        <td>順序変更</td>
                        <td>イメージ</td>
                        <td>卓編集</td>
                        <td>削除</td>
                    </tr>
                </table>
        	</div>
            
            <?php
			$i=0;
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
				if($i==0)
				{
					$chk = "checked='checked'";
					$default_id = $row['id'];
				}
				else
				{
					$chk='';
				}
			?>
            <div class="<?=$class?>">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td><?=$row['name']?></td>
                        <td>横<?=$row['row_number']?>列 × 縦<?=$row['column_number']?>段</td>
                        <td><?=$row['seat_number']?>名</td>
                        <td class="txt1">
                        	<a href="plans.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>&amp;page=<?=(int)$get['page']?>&amp;room_id=<?=(int)$row['room_id']?>">
                                ▲
                            </a> &nbsp;
                            <a href="plans.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>&amp;page=<?=(int)$get['page']?>&amp;room_id=<?=(int)$row['room_id']?>">
                                ▼
                            </a>
                        </td>
                        <td>
                        	<input type="radio" name="radio" id="plan_<?=$row['id']?>" value="radio" <?=$chk?> onclick="preview_plan(<?=$row['id']?>)" /> 
                               <label for="radio">プレビューする</label>
                        </td>
                        <td><a href="set_table_layout.php?default_plan_id=<?=$row['id']?>"><img src="img/common/btn_taku_edit.gif" width="52" height="17" /></a></td>
                        <td>
                        	<a href="javascript:void(0);" onClick="confirmDelete('plans.php?room_id=<?=(int)$_GET['room_id']?>&page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">
                            	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        	<?php
			$i++;
            }
			?>
        </div>
        
		<p></p>
        <div class="sekiji_table" id="table_preview">
			<?php
            
           
			foreach($data_rows as $row)
            {
            	if($row['id']==$default_id)
				{
					$display_prv = "display:block;";
				}
				else
				{
					$display_prv = "display:none;";
				}
            ?>
			<div class="preview" class="preview_<?=$row[1]?>"  id="prv_<?=$row['id']?>" style="<?=$display_prv?>">
               
				<?php       
        
                   $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$row['id']);
                    
                    $plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".$row['id']);
                    
                    $row_width = $plan_row['column_number'] *45;
                    
                    $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$row['id']);
					//print_r($tblrows);
                    ?>
        
                    <table style="width:100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td>
                        <div style="width:<?=$row_width?>px;margin: 0 auto; ">
                        <?php
                        foreach($tblrows as $tblrow)
                        {
                            $ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and default_plan_id=".(int)$row['id']." limit 1");                    
                        	if($ralign == 0)
							{
								$num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and default_plan_id=".(int)$row['id']." limit 1");
								
								if($num_none > 0)
								{
									$width = $row_width - ($num_none*41);
									$styles = "width:".$width."px;margin: 0 auto;";
								}
								else
								{
									$styles = "";
								}
							}
						?>
                            <div class="rows" style="float:left;width:100%" id="row_<?=$tblrow['row_order']?>"> 
                                <input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />            	
                                <div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$styles?>">
                                <?php                
                                $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where default_plan_id = ".(int)$row['id']." and row_order=".$tblrow['row_order']." order by  column_order asc");
                              
								foreach($table_rows as $table_row)
                                {										
                                   
                                        $tblname = $table_row['name'];
                                   
                                    
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
                                    <div class="tables" style=" <?php echo $disp;?>">
                                        <p align="center" style="text-align:center;" id="table_<?=$table_row['id']?>">                    
                                            <a href="#"><?=$tblname?></a> &nbsp;                                
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
                       </td>
                       </tr>
                    </table>
          
                
            </div>
            <?php
             }
        
             ?>
            
    	</div>
    </div>
</div>


<?php
	include_once('inc/left_nav.inc.php');
	
	include_once("inc/new.footer.inc.php");
?>
