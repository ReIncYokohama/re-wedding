<?php 
include('admin/inc/dbcon.inc.php');

$catid='';

if(!empty($_GET['catId'])){
	$catid=$_GET['catId'];
}
$countSearchRes=0;

if($catid!=''){
	$sql="select * from gaiji_buso where buso_id='".$catid."' ";
	$result=mysql_query($sql);
	$countSearchRes = mysql_num_rows($result);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script language="javascript" src="js/jquery.rollover.js" type="text/javascript"></script>

<script type="text/javascript">
/*
$(document).ready(function(){
  $("#ColorChange td").click(function(){
  $(this).css("background", "#fff");
  });
});
*/



function changeColor(id){

//alert("id_"+ id);

var idChangeColor="id_"+ id;
$(".color_text").css("color","black");
$("#"+idChangeColor).css("color","red");

$("#buttonShowImg").html('<img onclick="showSubCatImageAll('+id+');" src="img/search_off.jpg" />');


}

function showSubCatImageAll(id)
{

var catId = id;

$.post("get_subcat_by_cat.php", { catId: catId},
   function(data) {
     $('#subcat_img_show').html(data);	
   });

}


function showImageBox(id)
{
var catId = id;
//alert(catId);
$.post("get_subcat_one.php", { catId: catId},
   function(data) {
     $('#bigimage').html(data);
	 $("#clearImg").html('<img onclick="clearImage('+id+');" src="img/clear_off.jpg" />');
   });
}


function clearImage(id)
{
var catId = id;
//alert(catId);

	 $("#bigimage").html('<img src="img/dummiy_select.gif" />');

}


</script>


</head>

<body>
<div style="width:500px; margin:auto; background:#DFEAF5">

	<div style="min-height:500px;">
	<div><img src="img/guiji_title.jpg" /></div>
	<div style="width:495px; margin:auto; padding-top:3px;">
		 <div style="width:180px; float:left;">
		   <div>
			   <div id="categoryimage" style="background:url(img/imput.jpg) top no-repeat ; padding-left:50px">
				<form action="palette.php" method="get" id="search" name="search">
				<input type="text" id="catId" name="catId" size="15" maxlength="10" style="height:15px; font-size:8px;; width:126px;" /> <br>
				 <img src="img/imput_comment.jpg" />
				</form>			   
			  <!-- <input type="text" name="search" style="height:15px; font-size:8px;; width:126px;" /><br>-->
			  </div>
			   <div id="category" style="background:#FFFFFF; height:290px;width:180px; overflow:auto;"><img src="img/bushu_list_title.jpg" />

<?php  if((int)$countSearchRes>0) { ?> 
<table width="106" border="0" cellspacing="0" cellpadding="0" style="margin-left:12px;">
  <tr>

	
  </tr>
  
  <tr>

  <td>

	<table width="140" border="0" align="left" cellpadding="0" cellspacing="1">
	  <?php  while( $row = mysql_fetch_assoc($result) ){?> 
			<tr>
 
  			
    		<td width="15" height="27">&nbsp;</td>
    		<td width="27" height="27" align="center" valign="middle" bgcolor="#FFFFFF">
			<img src="upload/img_bushu/<?=$row['buso_image_name']?>" alt="" name="bushu001" width="25" height="25" border="0" id="bushu" />
			</td>

    		<td onclick="changeColor(<?=$row['buso_id']?>);" id="id_<?=$row['buso_id']?>" class="color_text" width="100" height="27" >
			<?php echo $row['buso_name'];?>

			</td>
			
  			</tr>
		<?php }  ?>				
		</table>

	</td>

  </tr>
<tr>
   <td> 
	<input type="hidden" name="idHide" id="idHide" value=""/>
	</td>
  </tr>  
</table> 
<?php  } //end of if($countSearchRes>0)?>

			   </div>
			   <div style="padding-top:10px; text-align:center"><div id="buttonShowImg"><img src="img/search_off.jpg" /></div></div>
		   </div>
		   
		</div>
		<div style="float:left; width:310px;">
			<div>
			   <div id="rightimage"><img src="img/ans_title.jpg" /></div>
					<div style="height:300px; overflow:auto; background:#FFFFFF;" >
					<div id="subcat_img_show">
					
					</div>
					<!--
					<table width="276" cellspacing="0" cellpadding="0" border="0" align="center">
						<tr>
							<td>
							<table>
								<tr>
								
									<td width="52" valign="middle" height="52" bgcolor="#FFFFFF" align="center">
									<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="img/F7C7.png"></td>
									
									<td width="52" valign="middle" height="52" bgcolor="#FFFFFF" align="center">
									<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="img/F7C8.png"></td>
									
									<td width="50" valign="middle" height="50" bgcolor="#FFFFFF" align="center">
									<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="img/F7C9.png"></td>
									
									<td width="50" valign="middle" height="50" bgcolor="#FFFFFF" align="center">
									<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="img/F7CA.png"></td>
									
									<td width="50" valign="middle" height="50" bgcolor="#FFFFFF" align="center">
									<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="img/F7CC.png"></td>
								
								</tr>
							</table>
							</td>
						</tr>
					</table>
					-->
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					</div>	
					<div style="height:85px; background:#254192; padding:2px;">
						<div>
							<div id="bigimage" style="width:80px; background:#ffffff; height:80px; float:left"><img src="img/dummiy_select.gif" id="select" alt="選択漢字" /></div>
							<div style="width:220px; float:left; padding-top:15px; padding-left:3px;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
								<tr>
									<td><img src="img/seach2_off.jpg" /></td>
									<td><div id="clearImg"><img src="img/clear_off.jpg" /></div></td>
								</tr>
								<tr>
									<td><img src="img/ok_on.jpg" /></td>
									<td><img src="img/close_off.jpg" /></td>
								</tr>
							</table>
							</div>
						</div>
					</div>		   
			   </div>
		   </div>
		</div>
	</div>
	<div style="clear:both"> </div>
	</div>
</div>
</body>
</html>
