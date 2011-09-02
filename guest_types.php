<?php
require_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");
$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);



	if(isset($post['name']) && $post['name'] != '')
	{
		$id = (int)$post['insert_edit'];
		unset($post['insert_edit']);

		if($id <= 0)
		{
			$post['display_order'] = time();
			$lid = $obj->InsertData("spssp_guest_type", $post);
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
			$obj->UpdateData("spssp_guest_type", $post," id=".$id);
			$msg = 2;
		}

	}
	if($_GET['action']=='sort' && (int)$_GET['id'] > 0) // UCHIDA EDIT 11/08/22
	{
		$table = 'spssp_guest_type';

		$id = (int)$_GET['id'];
		$move = $_GET['move'];
		$obj->sortItem($table, $id, $move);
	}
	else if(isset($get['action']) && $get['action'] !== '')
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$obj->DeleteRow("spssp_guest_type", "id=".$id);
			$msg = 3;
		}
		else
		{
			$err = 11;
		}
	}
?>
 <script type="text/javascript">
 var kindArray=new Array(); // UCHIDA EDIT 11/08/22
</script>

  <div id="topnavi">
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      <h2>基本設定 &raquo; 区分</h2>
<!--       <div class="subtitle"></div> -->
      <p class="txt3"><!-- <a href="javascript:void(0);" onclick="new_table()"> <b>新規登録</b></a>--></p>
	    <div  id="new_table" style="width:100%;">
<!--         	<h2 id="title_bar">登録　区分: </h2> -->
			<div class="subtitle">登録　区分: </div>
        	<form action="guest_types.php" method="post" name="new_name">
            	<input type="hidden" name="insert_edit" id="insert_edit" value="0" />
                	区分 : &nbsp;<input type="text" name="name" id="name"  /> &nbsp; &nbsp; &nbsp;
                <a onclick="check_name()" href="javascript:void(0)">
						<img src="img/common/btn_regist_update_admin.jpg"" alt="登録">
				</a>
				&nbsp;
				<a onclick="cancel_new()" href="javascript:void(0)">
					<img border="0" alt="ｸﾘｱ" src="img/common/btn_clear_admin.jpg">
				</a>

            </form><br />

        </div>
      <div class="box_table" style="width:100%;">
        <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td width="10%">No.</td>
              <td>区分<span class="txt1"></td>
              <td>順序変更</td>
              <td>編集</td>
              <td>削除</td>
            </tr>
          </table>
        </div>
		 <?php
				$query_string="SELECT * FROM spssp_guest_type order by display_order DESC;";
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;
				if(is_array($data_rows))
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
			<script language="javascript" type="text/javascript">
			kindArray[<?=$i?>]="<?=$row['name']?>";
			</script>
			<div class="<?=$class?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['name']?></td>
							<td>
							<span class="txt1"><a href="guest_types.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>">▲</a>
							<a href="guest_types?action=sort&amp;move=down&amp;id=<?=$row['id']?>"> ▼</a></span>
							</td>
                            <td>
                            	<a href="#" onclick="edit_name(<?=$row['id']?>,'<?=$row['name']?>');">
                                	<img src="img/common/btn_edit.png" width="42" height="17" />
                                </a>
                            </td>
              				<td>
                            	<a href="javascript:void(0);" onClick="confirmDelete('guest_types.php?action=delete&id=<?=$row['id']?>');">
                                	<img src="img/common/btn_deleate.png" width="42" height="17" />
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
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
<script type="text/javascript">
$j(function(){

	var msg_html=$j("#msg_rpt").html();

	if(msg_html!='')
	{
		$j("#msg_rpt").fadeOut(5000);
	}
});

function new_table()
{
	$j("#insert_edit").val("0");
	$j("#name").val("");
	$j("#new_table").toggle("slow");
}
function cancel_new()
{
	$j("#name").val("");
	$j("#insert_edit").val("0");
}
function check_name()
{
	if($j("#name").val() == '')
	{
		alert("区分を入力してください");
		$j("#name").focus();
		return false;
	}

	 // UCHIDA EDIT 11/08/22
	var kindname = $j("#name").val();
	for (var ii=0;ii<kindArray.length;ii++) {
		if (kindArray[ii] == kindname) {
			alert("同じ区分が存在するため登録･更新ができませんでした");
			$("#name").focus();
			return false;
		}
	}

	document.new_name.submit();
}
function edit_name(id, name)
{
	$j("#insert_edit").val(id);
	$j("#name").val(name);
	$j("#title_bar").html("編集　区分");
	$j("#new_table").fadeOut(100);
	$j("#new_table").fadeIn(500);
}
</script>
