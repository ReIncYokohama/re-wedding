<?php
	include_once("inc/dbcon.inc.php");
	include_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);
	$user_id = (int)$_GET['user_id'];

	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");
		
	$categories = $obj->GetAllRowsByCondition(" spssp_guest_category "," user_id=".$user_id) ;
	

	$sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".$user_id) ;
	
	if(isset($_POST['ajax']) && $_POST['ajax'] != '')
	{
		$ajax_sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".$_POST['user_id']." and category_id=".(int)$_POST['catid']) ;
		
		foreach($ajax_sub_categories as $scat)
		{
			echo "<option value='".$scat['id']."'>".$scat['name']."</option>";
		}
		exit;
	}
	
	if($post['last_name'] != '' && $post['first_name'] != '' && $post['sub_category_id'] != '')
	{
	
		//$post['name'] = $post['first_name'].' '.$post['last_name'];
		$post['user_id'] = $user_id;
		$post['display_order'] = time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		//unset($post['first_name']);
		//unset($post['last_name']);
		
		$id = $obj->InsertData("spssp_guest", $post);
		if($id > 0)
		{
			redirect("guest_gift.php?msg=1&user_id=".$user_id);
		}
		else
		{
			redirect("guest_gift.php?err=1&user_id=".$user_id);
		}
	}
	
	include_once("inc/new.header.inc.php");
	
	include("inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow( "spssp_respect");
	
	
	$guest_types = $obj->GetAllRow( "spssp_guest_type");
	
	include("inc/return_dbcon.inc.php");
	if(isset($get['gid']) && (int)$get['gid'] > 0)
	{
		$guest_row = $obj->GetSingleRow(" spssp_guest ", " id=".(int)$get['gid']);
	}
	
	
?>

<style>
.cont_area
{
padding:0;
}
#new_guest input
{
width:180px;
}
#new_guest
{
padding:10px 0;
border:solid 1px #999999;

width:100%;

}
.new_guest_table select
{
width:130px;
}
.new_guest_table tr
{
height:25px;
}
.new_guest_table tr td
{
padding-left:10px;
}

#categories
{
padding:10px 0;
border:solid 1px #999999;
display:none;
width:100%;
margin:10px 0;
}
.data
{
	width:80%;
	margin:0 auto;
}
.data tr
{
height:25px;
}
</style>
<link rel="stylesheet" href="../css/base/jquery.ui.all.css">
<script src="../js/jquery-1.4.2.js"></script>
<script src="../js/external/jquery.bgiframe-2.1.1.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.mouse.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.draggable.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.resizable.js"></script>
<script src="../js/ui/jquery.ui.dialog.js"></script>
<script src="../js/ui/jquery.effects.core.js"></script>
<script src="../js/ui/jquery.effects.blind.js"></script>
<script src="../js/ui/jquery.effects.fade.js"></script>

<script type="text/javascript">
	var catid = 0;
	var subcat_id = 0;
	var user_id = 0;
	
	$(function(){
		user_id = $("#user_id").val();
		
		
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(3)").addClass("active");
		
		$(".new_guest_table tr:even").css('background-color','#ECF4FB');
		$(".data tr:even").css('background-color','#EDEDED');
		
		$( "#new_category" ).dialog({
			autoOpen: false,
			height: 200,
			width: 380,
			show: "fade",
			hide: "explode",
			modal: true,
			buttons: {
				"保存": function() {

						var cat_name = $("#cat_name").val();
						var cat_desc = $("#cat_desc").val();
									
						$.post('ajax/view_categories.php',{'cat_name':cat_name,'cat_desc':cat_desc,'new_cat':'new_cat','catid':catid,'user_id':user_id}, function (data){
							if(parseInt(data) > 0)
							{
								view_cats();
								load_new_cats();
							}	
							else
							{
								alert("Operation Failed");
							}																								  
						});
												
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
	
					$( this ).dialog( "close" );
				},
				閉じる:function() {
					catid = 0;
					$("#cat_name").val('');
					$("#cat_desc").val('');
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});
		
		
		$( "#new_subcategory" ).dialog({
			autoOpen: false,
			height: 150,
			width: 380,
			show: "fade",
			hide: "explode",
			modal: true,
			buttons: {
				"保存": function() {

						var subcat_name = $("#subcat_name").val();
						var cat_id = $("#category_id").val();
									
						$.post('ajax/view_categories.php',{'subcat_name':subcat_name,'new_subcat':'new_subcat','catid':cat_id,'subcat_id':subcat_id,'user_id':user_id}, function (data){
							if(parseInt(data) > 0)
							{
								view_sub_cats(cat_id);
								load_new_sub_cats(cat_id);
							}	
							else
							{
								alert("Operation Failed");
							}																								  
						});
												
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
	
					$( this ).dialog( "close" );
				},
				閉じる:function() {
					catid = 0;
					$("#subcat_name").val('');
		
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});
		
		
	});
	function toggle_new()
	{
		$("#new_guest").toggle(500);
	}
	function cancel_div(did)
	{
		$("#"+did).hide(500);
	}
	function load_subcats(catid)
	{

		var catid = $("#guest_category").val();
		$.post('new_guest.php',{'ajax':'ajax','catid':catid,'user_id':user_id}, function (data){
			$("#sub_category_id").fadeOut(100);
			$("#sub_category_id").html(data);
			$("#sub_category_id").fadeIn(200);
		});
	}
	function view_cats()
	{
		$("#categories").fadeOut(100);
		$.post('ajax/view_categories.php',{'view_cats':'view_cats','user_id':user_id}, function (data){
			$("#categories").html(data);
		});
		//$("#categories").append("<p class='txt3'><a>Add New Category</a></p>");
		
		$("#categories").fadeIn(300);
	}
	function view_defaut_cats()
	{
		$.post('ajax/view_default_categories.php',{'user_id':user_id}, function (data){		
			$("#categories").fadeOut(100);
			$("#categories").html(data);
			$("#categories").fadeIn(300);		
		});
	}
	function view_sub_cats(catid)
	{
		$("#categories").fadeOut(100);
		$.post('ajax/view_categories.php',{'view_sub_cats':'view_sub_cats','catid':catid,'user_id':user_id}, function (data){
			$("#categories").html(data);
		});
		
		$("#categories").fadeIn(300);
	}
	function add_category()
	{
		catid = 0;
		$("#cat_name").val('');
		$("#cat_desc").val('');
		$( "#new_category" ).dialog( "open");
	}
	
	function edit_cat(id, name, description)
	{
		catid = id;
		$("#cat_name").val(name);
		$("#cat_desc").val(description);
		$( "#new_category" ).dialog("open");
	}
	function delete_cat(id)
	{
		var is_delete = confirm("削除すると、既に登録されているこの区分の招待者情報なども失われる可能性があります。削除しますか？");
		if(is_delete)
		{
			$.post('ajax/view_categories.php',{'delete_cat':'delete_cat','catid':id,'user_id':user_id}, function (data){
				if(parseInt(data) > 0)
				{
					view_cats();
				}	
				else
				{
					alert("Operation Failed");
				}	
			});
		}
	}
	function add_subcategory()
	{
		subcat_id = 0;
		$("#subcat_name").val('');

		$( "#new_subcategory" ).dialog("open");
	}
	
	function load_new_cats()
	{
		$.post('ajax/view_categories.php',{'load_new_cat':'load_new_cat','user_id':user_id}, function (data){
			$("#guest_category").fadeOut(100);
			$("#guest_category").html(data);
			$("#guest_category").fadeIn(200);
		});	
	}
	
	function load_new_sub_cats(cat_id)
	{
		$("#guest_category").val(cat_id);
		$.post('ajax/view_categories.php',{'load_new_subcat':'load_new_subcat','cat_id':cat_id,'user_id':user_id}, function (data){
			
			$("#sub_category_id").fadeOut(100);
			$("#sub_category_id").html(data);
			$("#sub_category_id").fadeIn(200);
		});
	}
	
	function edit_subcat(id , name)
	{
		subcat_id = id;
		$("#subcat_name").val(name);
		$( "#new_subcategory" ).dialog("open");
	}
	function delete_subcat(id)
	{
		var is_delete = confirm("削除すると、既に登録されているこの区分の招待者情報なども失われる可能性があります。削除しますか？");
		var cat_id = $("#category_id").val();
		if(is_delete)
		{
			$.post('ajax/view_categories.php',{'delete_subcat':'delete_subcat','id':id,'user_id':user_id}, function (data){
				if(parseInt(data) > 0)
				{
					view_sub_cats(cat_id);
					load_new_sub_cats(cat_id);
				}	
				else
				{
					alert("Operation Failed");
				}	
			});
		}
	}
	function acceptDefaultCategory(did)
	{
		$.post('ajax/view_categories.php',{'accept_default':'accept_default','did':did,'user_id':user_id}, function (data){
		
			if(parseInt(data) > 0)
			{
				view_cats();
				load_new_cats();
				load_new_sub_cats(parseInt(data));
			}
			else
			{
				alert("Operation Failed");
			}
		});
	}
	function back_to_guest()
	{
		window.location = "guest_gift.php?user_id=<?=$user_id?>";
	}
	
	function insert_new_guest()
	{
		var last_name = $("#last_name").val();
		var first_name = $("#first_name").val();
		var sub_category_id = $("#sub_category_id").val();
		

		if(first_name == '')
		{
			alert("全て入力してください");
			$("#first_name").focus();
			return false;
		}

		if(last_name == '')
		{
			alert("全て入力してください");
			$("#last_name").focus();
			return false;
		}
		if(sub_category_id == null)
		{
			alert("全てを選択してください");
			$("#sub_category_id").focus();
			return false;
		}
		document.new_guest_form.submit();
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
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents"> 
    <div style="font-size:14; font-weight:bold;">
         <?=$user_row['man_firstname']?> 様・ <?=$user_row['woman_firstname']?> 様
    </div>
     <h2>       	
            	 <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報 &raquo; 席次表・引出物発注 &raquo; 招待者登録
        </h2>
		<div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      	<div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      	<div class="navi">
      		<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
      			<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
        	</a>
      	</div>
      	<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>"><img src="img/common/navi04_on.jpg" width="150" height="22"/></a></div>
      	<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      	<div style="clear:both;"></div></div>
      	<br />
        <!-- <a href="#" onclick="insert_item();">Add Category</a> <br />-->
     <p><a href="new_guest.php?user_id=<?=$user_id?>"> <b>招待者登録</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <a href="gift_user.php?user_id=<?=$user_id?>"> <b>引出物設定</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <a href="menu_user.php?user_id=<?=$user_id?>"> <b>料理設定（子供料理）</b></a>
     
   </p><br />
		<p class="txt3">
            <a href="javascript:void(0);" onclick="view_cats()">区分一覧</a> &nbsp;
            <a href="javascript:void(0);" onclick="view_defaut_cats()">区分テンプレート一覧</a>
    	</p>
	
    	<div class="box6">
    		<div id="categories">
    			<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" class="data">
        			
					<?php
                    
                        foreach($categories as $cat)
                        {
                            
                    ?>
                            <tr>
                                <td><a href="javascript:void(0);" onclick="view_sub_cats(<?=$cat['id']?>)"><?=$cat['name']?></a></td>
                                <td><?=$cat['description']?></td>
                            </tr>
                    <?php	
                        }
                    ?>
        		</table>
    		</div>
            
            <div id="new_guest">
            	<input type="hidden" id="user_id" value="<?=$user_id?>" />
                <form action="new_guest.php?user_id=<?=$user_id?>" method="post" name="new_guest_form">
                <table style="width:500px; margin:0 auto; " border="0" cellspacing="3" cellpadding="4" align="center" class="new_guest_table" align="center">
                    <tr>
                        <td>新郎側・新婦側</td>
                        
                        <td>
                            
                            <select id="sex" name="sex">
                                <option value="Male" <?php if($guest_row['sex']=="Male"){ echo "Seleceted='Selected'"; }?> >新郎側</option>
                                <option value="Female" <?php if($guest_row['sex']=="Female"){ echo "Seleceted='Selected'"; }?> >新婦側</option>
                            </select>
                        
                        </td>
                     </tr>
                     
                     <tr>
                        
                        <td> 名:</td>
                        <td><input type="text" name="first_name" id="first_name" <?=$guest_row['first_name']?> /></td>
                     </tr>
                     
                     <tr>
                        <td> 姓:</td>
                        <td><input type="text" name="last_name" id="last_name" <?=$guest_row['last_name']?> /></td>
                     </tr>
                     
                     <tr>                
                        <td> 敬称:</td>
                        <td>
                            <select id="respect_id" name="respect_id" >
                                <?php
                                    foreach($respects as $respect)
                                    {
                                        if($guest_row['respect_id'] == $respect['id'])
                                        {
                                            $sel = "Selected='Selected'";
                                        }
                                        else
                                        {
                                            $sel = " ";
                                        }
                                        
                                        echo "<option value='".$respect['id']."'  $sel >".$respect['title']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                     </tr>
                     <tr>
                        
                        <td> 区分:</td>
                        <td>
                            <select id="guest_type" name="guest_type">
                                <?php
                                    foreach($guest_types as $guest_type)
                                    {
                                        echo "<option value='".$guest_type['id']."'>".$guest_type['name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                     </tr>
                     <tr>
                        
                        <td> 肩書 1:</td>
                        <td><input type="text" name="comment1" id="comment1" maxlength="40" <?=$guest_row['comment1']?> /></td>
                     </tr>
                     <tr>
                        
                        <td> 肩書 2:</td>
                        <td><input type="text" name="comment2" id="comment2" maxlength="40" <?=$guest_row['comment2']?> /></td>
                     </tr>
        
                    <tr>
        
                        <td> 席次表用区分</td>
                        <td>
                            <select id="guest_category" onchange="load_subcats();" >
                                <?php
                                    $qry = "SELECT c.id,  FROM `spssp_guest` g inner join spssp_guest_sub_category sc on g.sub_category_id = sc.id inner join 
                                    spssp_guest_category c on sc.category_id=c.id where g.id=".$guest_row['id'];
                                    $result = mysql_query($qry);
                                    $data = mysql_fetch_assoc($result);
                                    $catid = $data['id'];
                                    foreach($categories as $category)
                                    {
                                        if($category['id'] == $catid)
                                        {
                                            $sel = "Selected='Selected'"; 
                                        }
                                        else
                                        {
                                            $sel = " ";
                                        }
                                        echo "<option value='".$category['id']."' $sel >".$category['name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> 席次表用区分詳細</td>
                        <td>
                            <select id="sub_category_id" name="sub_category_id">
                                <?php
                                    foreach($sub_categories as $sub_category)
                                    {
                                        echo "<option value='".$sub_category['id']."'>".$sub_category['name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="button" value="保存" style="width:80px; background-color:#0FC9F8; color:#FFFFFF;" onclick="insert_new_guest()" /> &nbsp;
        
                            <input type="button" value="戻る" style="width:80px; background-color:#0FC9F8; color:#FFFFFF;" onclick="back_to_guest()" />
                        </td>
                        
                        
                    </tr>
                </table>
                </form>
    		</div>
            <div id="new_category" title="区分新規登録" >
                <table width="80%" cellpadding="3" cellspacing="0" border="0" align="center">
                    <tr>
                        <td> 席次表用区分</td>
                        <td><input type="text" id="cat_name" /></td>
                    </tr>
                    <tr>
                        <td> 内容</td>
                        <td><input type="text" id="cat_desc" /></td>
                    </tr>
                </table>
            </div>
            
            <div id="new_subcategory" title="区分テンプレート一覧" >
                <table width="80%" cellpadding="3" cellspacing="0" border="0" align="center">
                    <tr>
                        <td> 席次表用区分詳細</td>
                        <td><input type="text" id="subcat_name" /></td>
                    </tr>
        
                </table>
            </div>
            
    	</div>
    </div>
</div>
<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
