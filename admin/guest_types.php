<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	
	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);
	
	if($_SESSION['user_type'] != 111)
	{
		redirect("manage.php");
	}

	
	if(isset($post['name']) && $post['name'] != '')
	{
		$id = (int)$post['insert_edit'];
		unset($post['insert_edit']);
		
		if($id <= 0)
		{
			$lid = $obj->InsertData("dev2_main.spssp_guest_type", $post);
			if($lid > 0)
			{
				$msg = 1;
			}
			else
			{
				$err = 1;
			}
		}
		else
		{
			$obj->UpdateData("dev2_main.spssp_guest_type", $post," id=".$id);
			$msg = 2;
		}

	}
	if(isset($get['action']) && $get['action'] !== '')
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$obj->DeleteRow("dev2_main.spssp_guest_type", "id=".$id);
			$msg = 3;
		}
		else
		{
			$err = 11;
		}
	}
	
?>
<script type="text/javascript">
$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});

function new_table()
{
	$("#insert_edit").val("0");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{
	$("#new_table").fadeOut(300);
}
function check_name()
{
	if($("#name").val() == '')
	{
		alert("「区分を入力してください」");
		$("#name").focus();
		return false;
	}
	document.new_name.submit();
}
function edit_name(id, name)
{
	$("#insert_edit").val(id);
	$("#name").val(name);
	$("#title_bar").html("編集　区分");
	$("#new_table").fadeOut(100);
	$("#new_table").fadeIn(500);
}
</script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="login.html"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

	<div style="clear:both;"></div>   
	<div id="contents">
		<h2> 席次表・席札  &raquo; 区分の設定</h2>
		<!--<p class="txt3">
            <a href="default.php"><b>テーブル名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="respects.php"><b>敬称</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
             <b>区分</b>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="religions.php"><b>挙式種類</b></a>
      </p>-->
        <?php if(isset($err)){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
		<?php if(isset($msg)){echo "<script>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>";}?> 
        <p class="txt3">
        	<a href="javascript:void(0);" onclick="new_table()"> <b>新規登録</b></a>			
        </p>
		
        <div  id="new_table" style="display:none; width:100%;">
        	<h2 id="title_bar">登録　区分: </h2>
        	<form action="guest_types.php" method="post" name="new_name">
            	<input type="hidden" name="insert_edit" id="insert_edit" value="0" />
                	区分 : &nbsp;<input type="text" name="name" id="name"  /> &nbsp; &nbsp; &nbsp;
                <input type="button" onclick="check_name();" value="保存" /> &nbsp;
                <input type="button" onclick="cancel_new();" value="キャンセル" /> &nbsp;
                
            </form><br />

        </div>
        <p>&nbsp;</p>
        <div id="message_BOX" style="height:450px; overflow:auto;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>区分</td>
             
                  <td>編集</td>
                  <td>削除</td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM dev2_main.spssp_guest_type  ORDER BY name asc ;";
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;
						
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
			?>
            	 <div class="<?=$class?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['name']?></td>
                            <td>
                            	<a href="#" onclick="edit_name(<?=$row['id']?>,'<?=$row['name']?>');">
                                	<img src="img/common/btn_edit.gif" width="42" height="17" />
                                </a>
                            </td>
              				<td>
                            	<a href="javascript:void(0);" onClick="confirmDelete('guest_types.php?action=delete&id=<?=$row['id']?>');">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}
			 ?>
        
			</div>
        </div>
 </div>
<?php	
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>