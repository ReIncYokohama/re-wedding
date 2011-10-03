<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$row = $obj->GetSingleRow('spssp_room',' id='.$get['id']);
	
	
	if(trim($_POST['name']) && trim($_POST['max_rows']) && trim($_POST['max_columns']))
	{
		
		$post = $obj->protectXSS($_POST);
		
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		
		$obj->UpdateData('spssp_room',$post," id=".$get['id']);
		
		if(($post['max_rows'] != $row['max_rows']) || ($post['max_columns']!=$row['max_columns']) || ($post['max_seats']!=$row['max_seats']))
		{
			$obj->DeleteRow("spssp_default_plan_table", " room_id=".$get['id']);
			
			for($i=1; $i <= (int)($row['max_rows']) *($row['max_columns']); $i++)
			{
				$table_arr['name'] = 'Table'.$i;
				$table_arr['room_id'] = $get['id'];
				$ltid = $obj->InsertData('spssp_default_plan_table',$table_arr);
				if($ltid > 0)
				{
					for($j=1; $j <= (int)$row['max_seats']; $j++)
					{
						$sit_arr['table_id'] = $ltid;
						$stid = $obj->InsertData('spssp_default_plan_seat',$sit_arr);
						if($stid >0)
						{
							redirect("rooms.php?page=".(int)$_GET['page']);
						}
					}
				}
			}
		}
		
		redirect("rooms.php?page=".(int)$_GET['page']);
		
	}
?>
<link href="./calendar/cwcalendar.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript">
function validForm()
{
	
	var name  = document.getElementById('name').value;
	var max_rows  = document.getElementById('max_rows').value;
	var max_columns  = document.getElementById('max_columns').value;
	var max_seats  = document.getElementById('max_seats').value;
	var flag = true;
	if(!name)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if(!max_rows)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('max_rows').focus();
		 flag = false;
	}
	if(!max_columns)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('max_columns').focus();
		flag = false;
	}	
	if(!max_seats)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('max_seats').focus();
		 flag = false;
	}
	if(flag == true)
	{	
		document.room_form.submit();
	}	
}
	var formatSplitter = "-";
      var monthFormat = "dd";
      var monthFormat = "mm";
      var yearFormat = "yyyy";
</script>
<script type="text/javascript" src="calendar/calendar.js"></script>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="rooms.php?page=<?=(int)$_GET['page']?>">Rooms</a>  &raquo; Edit Room
</div>
<h2> &nbsp;Edit Room</h2>
<form action="room_edit.php?page=<?=(int)$_GET['page']?>&amp;id=<?=(int)$_GET['id']?>" method="post" name="room_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">Room Name</td>
			<td style="text-align:left;">
            	<input type="text" name="name" style="width:250px;" id="name" value="<?=$row['name']?>"/>
            </td>
		</tr>
        <tr>
        	<td style="text-align:right;">Max Rows</td>
			<td style="text-align:left;">
            	<input type="text" name="max_rows" style="width:250px;" id="max_rows" value="<?=$row['max_rows']?>"/>
            </td>
        </tr>
        
        <tr>
        	<td style="text-align:right;">Max Tables in each Row</td>
			<td style="text-align:left;">
            	<input type="text" name="max_columns" style="width:250px;" id="max_columns" value="<?=$row['max_columns']?>"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Max Seats in each Table</td>
			<td style="text-align:left;">
            	<input type="text" name="max_seats" style="width:250px;" id="max_seats" value="<?=$row['max_seats']?>"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Hotel Room タイトル</td>
			<td style="text-align:left;">
            	<input type="text" name="hotel_room_title" id="hotel_room_title" style="width:250px;" value="<?=$row['hotel_room_title']?>"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Hotel Room Date</td>
			<td style="text-align:left;">
            	<input type="text" name="hotel_room_date"  value="<?=$row['hotel_room_date']?>"  id="hotel_room_date" style="width:250px;" readonly />
                <img src="calendar/cal.gif" align="absmiddle" onclick="fPopCalendar('hotel_room_date');"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Hotel Free Text</td>
			<td style="text-align:left;">
            	<textarea name="hotel_free_text" style="width:250px;"/> <?=$row['name']?></textarea>
            </td>
        </tr>


        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="button"  value="保存" onclick="validForm();" /> &nbsp; <input type="button" value="リセット" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>

