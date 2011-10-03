<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	$id = (int)$_GET['id'];
	$table = "spssp_default_plan";
	
	$rooms = $obj->GetSingleRow("spssp_room", " id=".(int)$_GET['room_id']);
	
	if($id > 0)
	{
		$row = $obj->GetSingleRow($table,' id='.$id);
	}
	
	if(trim($_POST['name']) && trim($_POST['row_number']) && $id > 0 )
	{
		$post = $obj->protectXSS($_POST);
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['room_id'] = (int)$_GET['room_id'];
		$lastid = $obj->UpdateData($table,$post," id=".$id);
		

		redirect("plans.php?page=".(int)$_GET['page']."&amp;room_id=".(int)$_GET['room_id']);
		
	}
?>

<script type="text/javascript">	

function validForm()
{
	var name  = document.getElementById('name').value;
	//var room_id  = document.getElementById('room_id').value;
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
		 alert("メールアドレスが未入力です");
		 document.getElementById('name').focus();
		 return false;
		
	}

	if(!row_number)
	{
		 alert("メールアドレスが未入力です");
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
		 alert("メールアドレスが未入力です");
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
		 alert("メールアドレスが未入力です");
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

/*function loadRoomInfo(id)
{
	$.post("check_room.php", {id:id},function(data){
		var values = data.split(",");
		$("#max_rows").val(values[0]);
		$("#max_columns").val(values[1]);
		$("#max_seats").val(values[2]);
	});
}*/

</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="rooms.php">Rooms </a> &raquo;<a href="plans.php?page=<?=(int)$_GET['page']?>">Default Plans</a>  &raquo;
    <?php
    	if((int)$_GET['id'] > 0)
		{
			echo "Edit Plan";
		}
		else
		{			
			echo "New Plan";
		}
	?>

</div><br />


<form action="plan_edit.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>&room_id=<?=(int)$_GET['room_id']?>" method="post" name="plan_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">Plan Name</td>
			<td style="text-align:left;">
            	<input type="text" name="name" style="width:250px;" id="name" value="<?=$row['name']?>"/>
            </td>
		</tr>

        
        <tr>
			<td style="text-align:right;">Number of Rows</td>
			<td style="text-align:left;">
            	<input type="text" name="row_number" style="width:250px;" id="row_number" value="<?=$row['row_number']?>"/>
                
                <input type="hidden"  id="max_rows" value="<?=$rooms['max_rows']?>" />
                <input type="hidden"  id="max_columns" value="<?=$rooms['max_columns']?>" />
                <input type="hidden"  id="max_seats" value="<?=$rooms['max_seats']?>" />
            </td>
		</tr>
        
        <tr>
			<td style="text-align:right;">Number of Columns</td>
			<td style="text-align:left;">
            	<input type="text" name="column_number" style="width:250px;" id="column_number" value="<?=$row['column_number']?>"/>
            </td>
		</tr>
        
        <tr>
			<td style="text-align:right;">Number of Seats</td>
			<td style="text-align:left;">
            	<input type="text" name="seat_number" style="width:250px;" id="seat_number" value="<?=$row['seat_number']?>"/>
            </td>
		</tr>
        

        
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="button"  value="保存" onClick="validForm();" /> &nbsp; <input type="button" value="リセット" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>

