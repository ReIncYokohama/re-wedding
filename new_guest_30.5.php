<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	//$get = $obj->protectXSS($_GET);
	//$post = $obj->protectXSS($_POST);
	$user_id = (int)$_SESSION['userid'];
	
	//echo'<pre>';
	//print_r($_POST);
	//exit;
	


if($_POST['first_name'] !='' && (int)$_POST['id'] >0)
{	
  $query_string="update spssp_guest set user_id='".$user_id."',respect_id='".$_POST['respect_id']."',sex='".$_POST['sex']."',last_name='".jp_encode($_POST['last_name'])."',first_name='".jp_encode($_POST['first_name'])."',guest_type='".$_POST['guest_type']."',comment1='".jp_encode($_POST['comment1'])."',comment2='".jp_encode($_POST['comment2'])."',memo='".jp_encode($_POST['memo'])."'  where id=".$_POST['id'];
	//echo $query_string;
	mysql_query($query_string);
	
	$gift_group = $_POST['gift_group'];
	$menu_grp = $_POST['menu_grp'];
			
	//if(isset($gift_group) && $gift_group != '')
	//{
		
		$num_guest_gift = $obj->GetNumRows("spssp_guest_gift"," guest_id=".(int)$_POST['id']." and user_id=".$user_id);
		if($num_guest_gift <= 0)
		{
			$query_string="INSERT INTO spssp_guest_gift (guest_id,group_id,user_id) VALUES ('".$_POST['id']."','".$_POST['gift_group']."','".$user_id."');";
	        mysql_query($query_string);
		}
		else
		{
			$query_string="update spssp_guest_gift set group_id='".$_POST['gift_group']."' where user_id=".$user_id." and guest_id=".$_POST['id'];
			//echo $query_string;
			mysql_query($query_string);
		}
				
	//}
	
	//if(isset($menu_grp) && $menu_grp != '')
	//{			
		
		$num_guest_gift = $obj->GetNumRows("spssp_guest_menu"," guest_id=".(int)$_POST['id']." and user_id=".$user_id);
		
		if($num_guest_gift <= 0)
		{
			$query_string="INSERT INTO spssp_guest_menu (guest_id,menu_id,user_id) VALUES ('".$_POST['id']."','".$_POST['menu_grp']."','".$user_id."');";
	        mysql_query($query_string);
		}
		else
		{
			$query_string="update spssp_guest_menu set menu_id='".$_POST['menu_grp']."' where user_id=".$user_id." and guest_id=".$_POST['id'];
			mysql_query($query_string);
		}
	//}
			
	redirect("my_guests.php?page=".$_GET['page']);
	exit;
}

if($_POST['first_name'] !='' )
{	
  $query_string="INSERT INTO spssp_guest (user_id,respect_id,sex, display_order,creation_date,last_name,first_name,guest_type,comment1,comment2,memo) VALUES ('".$user_id."','".$_POST['respect_id']."','".$_POST['sex']."','".time()."','".date("Y-m-d H:i:s")."','".jp_encode($_POST['last_name'])."','".jp_encode($_POST['first_name'])."','".$_POST['guest_type']."','".jp_encode($_POST['comment1'])."','".jp_encode($_POST['comment2'])."','".jp_encode($_POST['memo'])."');";
	mysql_query($query_string);
	$guestId = mysql_insert_id();
	
	$gift_group = $_POST['gift_group'];
	$menu_grp = $_POST['menu_grp'];
			
	if(isset($gift_group) && $gift_group != '')
	{
		$query_string="INSERT INTO spssp_guest_gift (guest_id,group_id,user_id) VALUES ('".$guestId."','".$gift_group."','".$user_id."');";
		mysql_query($query_string);		
	}
	
	if(isset($menu_grp) && $menu_grp != '')
	{
		$query_string="INSERT INTO spssp_guest_menu (guest_id,menu_id,user_id) VALUES ('".$guestId."','".$menu_grp."','".$user_id."');";
		mysql_query($query_string);
	}
	
	
	redirect("my_guests.php?page=".$_GET['page']);
	exit;
}
					
	$categories = $obj->GetAllRowsByCondition(" spssp_guest_category "," user_id=".(int)$_SESSION['userid']) ;

	$sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".(int)$_SESSION['userid']) ;
	
	if(isset($_POST['ajax']) && $_POST['ajax'] != '')
	{
		$ajax_sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".(int)$_SESSION['userid']." and category_id=".(int)$_POST['catid']) ;
		foreach($ajax_sub_categories as $scat)
		{
			echo "<option value='".$scat['id']."'>".$scat['name']."</option>";
		}
		exit;
	}
	
	if($post['last_name'] != '' && $post['first_name'] != '' && $post['sub_category_id'] != '')
	{
	
		
		//$post['name'] = $post['first_name'].' '.$post['last_name'];
		$post['user_id'] = (int)$_SESSION['userid'];
		$post['display_order'] = time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		//unset($post['first_name']);
		//unset($post['last_name']);
		$gid = (int)$_GET['gid'];
		if($gid <= 0)
		{		
			
			$id = $obj->InsertData("spssp_guest", $post);
		}
		else
		{

			$gift_group = $post['gift_group'];
			$menu_grp = $post['menu_grp'];
			unset($post['gift_group']);
			unset($post['menu_grp']);
			$obj->UpdateData("spssp_guest",$post,' id= '.$gid);
			
			if(isset($gift_group) && $gift_group != '')
			{

				$num_guest_gift = $obj->GetNumRows("spssp_guest_gift"," guest_id=".(int)$gid." and user_id=".$user_id);
				
				if($num_guest_gift <= 0)
				{
					$gift['guest_id'] = (int)$gid;
					$gift['group_id'] = $gift_group;
					$gift['user_id'] = $user_id;
					
					$lg_id = $obj->InsertData("spssp_guest_gift", $gift);
				}
				else
				{
					$gift['group_id'] = $gift_group;					
					$obj->UpdateData("spssp_guest_gift", $gift," user_id=".$user_id." and guest_id=".(int)$gid);
				}
			}
			
			if(isset($menu_grp) && $menu_grp != '')
			{			
				$num_guest_gift = $obj->GetNumRows("spssp_guest_menu"," guest_id=".(int)$gid." and user_id=".$user_id);
				
				if($num_guest_gift <= 0)
				{
					$menu['guest_id'] = (int)$gid;
					$menu['menu_id'] = $menu_grp;
					$menu['user_id'] = $user_id;
					
					$lmid = $obj->InsertData("spssp_guest_menu", $menu);
				}
				else
				{
					$menu['menu_id'] = $menu_grp;
					$obj->UpdateData("spssp_guest_menu", $menu," user_id=".$user_id." and guest_id=".(int)$gid);
				}
			}
			
			redirect("my_guests.php?msg=2");
			exit;
			
		}
		if($id > 0)
		{
			redirect("my_guests.php?msg=1");
		}
		else
		{
			redirect("new_guest.php?err=1");
		}
	}
	
	include_once("inc/new.header.inc.php");
	
	$respects = $obj->GetAllRow("spssp_respect");
	
	$guest_types = $obj->GetAllRow("spssp_guest_type");
	
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
#categories
{
padding:10px 0;
border:solid 1px #999999;
display:none;
width:100%;
margin:10px 0;
}

</style>
<script type="text/javascript">
	var catid = 0;
	var subcat_id = 0;
	$(function(){
		
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(3)").addClass("active");
		
		$(".new_guest_table tr:even").css('background-color','#ECF4FB');
		$(".data tr:even").css('background-color','#EDEDED');
		
		var msg_html=$("#msg_rpt").html();
	
		if(msg_html!='')
		{
			$("#msg_rpt").fadeOut(5000);
		}
		
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
						
						if(cat_name=="")
						{
							alert("category name must not be empty");
						}
						else if(cat_desc=="")
						{
							alert("category description must not be empty");
						}
						else
						{			
							$.post('ajax/view_categories.php',{'cat_name':cat_name,'cat_desc':cat_desc,'new_cat':'new_cat','catid':catid}, function (data){
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
						}
						
					
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
									
						$.post('ajax/view_categories.php',{'subcat_name':subcat_name,'new_subcat':'new_subcat','catid':cat_id,'subcat_id':subcat_id}, function (data){
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
		$.post('new_guest.php',{'ajax':'ajax','catid':catid}, function (data){
			$("#sub_category_id").fadeOut(100);
			$("#sub_category_id").html(data);
			$("#sub_category_id").fadeIn(200);
		});
	}
	function view_cats()
	{
		$("#categories").fadeOut(100);
		$.post('ajax/view_categories.php',{'view_cats':'view_cats'}, function (data){
			$("#categories").html(data);
		});
		//$("#categories").append("<p class='txt3'><a>Add New Category</a></p>");
		
		$("#categories").fadeIn(300);
	}
	function view_defaut_cats()
	{
		$.post('view_default_categories.php', function (data){		
			$("#categories").fadeOut(100);
			$("#categories").html(data);
			$("#categories").fadeIn(300);		
		});
	}
	function view_sub_cats(catid)
	{
		$("#categories").fadeOut(100);
		$.post('ajax/view_categories.php',{'view_sub_cats':'view_sub_cats','catid':catid}, function (data){
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
		var is_delete = confirm("If You delete it, all Guests and Sub-categories of this category will be removed.Are Sure to Delete This Category ???");
		if(is_delete)
		{
			$.post('ajax/view_categories.php',{'delete_cat':'delete_cat','catid':id}, function (data){
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
		$.post('ajax/view_categories.php',{'load_new_cat':'load_new_cat'}, function (data){
			$("#guest_category").fadeOut(100);
			$("#guest_category").html(data);
			$("#guest_category").fadeIn(200);
		});	
	}
	
	function load_new_sub_cats(cat_id)
	{
		$("#guest_category").val(cat_id);
		$.post('ajax/view_categories.php',{'load_new_subcat':'load_new_subcat','cat_id':cat_id}, function (data){
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
		var is_delete = confirm("If You delete it, all Guests of this Sub-Category will be removed.Are Sure to Delete This Sub-Category ???");
		var cat_id = $("#category_id").val();
		if(is_delete)
		{
			$.post('ajax/view_categories.php',{'delete_subcat':'delete_subcat','id':id}, function (data){
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
		$.post('ajax/view_categories.php',{'accept_default':'accept_default','did':did}, function (data){
		
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
		window.location = "my_guests.php";
	}
	
	function insert_new_guest()
	{
		var last_name = $("#last_name").val();
		var first_name = $("#first_name").val();
		var sub_category_id = $("#sub_category_id").val();
		

		if(last_name == '')
		{
			alert("姓を入力してください");
			$("#last_name").focus();
			return false;
		}
		if(first_name == '')
		{
			alert("名を入力してください");
			$("#first_name").focus();
			return false;
		}
		if(sub_category_id == null)
		{
			alert("区分詳細を選択しってください");
			$("#sub_category_id").focus();
			return false;
		}
		document.new_guest_form.submit();
	}
	
</script>
<div id="step_bt_area">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><img src="img/step_head_bt03_on.jpg" width="155" height="60" border="0"/></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="clear"></div></div>

<div id="main_contents">
  <div class="title_bar">
    <div class="title_bar_txt_L">招待者リストの作成を行います。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
	<div class="cont_area">
    <p>
    	<a href="javascript:void(0);" onclick="view_cats()">区分一覧</a> &nbsp;
        <a href="javascript:void(0);" onclick="view_defaut_cats()">区分テンプレート一覧</a>
    </p>
    <?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				$obj->GetErrorMsg((int)$_GET['err']);

			}
			else if(isset($_GET['msg']) && $_GET['msg']!='')
			{
				$obj->GetSuccessMsg((int)$_GET['msg']);
			}
	?>
    <div id="categories">
    	<!--<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" class="data">
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
		-->
    </div>
	<div id="new_guest">
    	<form action="new_guest.php?gid=<?=(int)$_GET['gid']?>" method="post" name="new_guest_form">
    	<table width="500" border="0" cellspacing="1" cellpadding="3" align="center" class="new_guest_table">
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
                <td><input type="text" name="first_name" id="first_name" value="<?=$guest_row['first_name']?>" /></td>
             </tr>
             
             <tr>
            	<td> 姓:</td>
                <td><input type="text" name="last_name" id="last_name" value="<?=$guest_row['last_name']?>" /></td>
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
								if($guest_row['guest_type'] == $guest_type['id'])
								{
									$sel = "Selected='Selected'";
								}
								else
								{
									$sel = " ";
								}
								echo "<option value='".$guest_type['id']."' $sel>".$guest_type['name']."</option>";
							}
						?>
                    </select>
                </td>
             </tr>
             <tr>
                
                <td> 肩書 1:</td>
                <td><input type="text" name="comment1" id="comment1" maxlength="40" value="<?=$guest_row['comment1']?>" /></td>
             </tr>
             <tr>
                
                <td> 肩書 2:</td>
                <td><input type="text" name="comment2" id="comment2" maxlength="40" value="<?=$guest_row['comment2']?>" /></td>
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
                <td>席次表用区分詳細</td>
                <td>
                	<select id="sub_category_id" name="sub_category_id">
                    	<?php
							foreach($sub_categories as $sub_category)
							{
								if($sub_category['id'] == $guest_row['sub_category_id'])
								{
									$sel = "Selected='Selected'"; 
								}
								else
								{
									$sel = " ";
								}
							
								echo "<option value='".$sub_category['id']."' $sel >".$sub_category['name']."</option>";
							}
						?>
                    </select>
                </td>
            </tr>
            <?php
			if((int)$get['gid'] > 0)
			{
			?>
            <tr>
                <td> 引出物 </td>
                <td> 
                    <?php
                        $gift_groups = $obj->GetAllRowsByCondition(" spssp_gift_group "," user_id=".$user_id);
                        
                        $guest_gifts = $obj->GetAllRowsByCondition(" spssp_guest_gift ","guest_id=".$get['gid']." and user_id=".$user_id);
                        $gg_arr = array();
                        foreach($guest_gifts as $gg)
                        {
                            $gg_arr[] = $gg['group_id'];
                        }
                        
                        echo "<select id='gift_group".$row['id']."' onchange='".$row['id']."' name='gift_group'>";
                        foreach($gift_groups as $gg)
                        {
                            if(in_array($gg['id'],$gg_arr))
                            {
                                $sel = "selected='selected'";
                            }
                            else
                            {
                                $sel = ""; 
                            }
                            echo "<option value='".$gg['id']."' $sel >".$gg['name']."</option>";
                        }
                        echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>                        
                <td> 料理 </td>
                <td>
                    <?php
                        $menus = $obj->GetAllRowsByCondition(" spssp_menu_group "," user_id=".$user_id);
                        
                        $guest_menus = $obj->GetAllRowsByCondition(" spssp_guest_menu "," guest_id=".$get['gid']." and user_id=".$user_id);
                    
                        $gm_arr = array();
                        foreach($guest_menus as $gm)
                        {
                            $gm_arr[] = $gm['menu_id'];
                        }
                        
                        echo "<select id='menu_".$row['id']."' name='menu_grp'>";
						echo "<option value='0'>なし</option>";
                    
                        foreach($menus as $m)
                        {
                            if(in_array($m['id'],$gm_arr))
                            {
                                $sel = "selected='selected'";
                            }
                            else
                            {
                                $sel = ""; 
                            }
                            echo "<option value='".$m['id']."' $sel >".$m['name']."</option>";
                        }
                        echo "</select>";
                        
                    ?>
                </td>
                
            </tr>
			<?php
			}
			?>
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
            	<td>席次表用区分</td>
                <td><input type="text" id="cat_name" /></td>
            </tr>
            <tr>
            	<td>本文</td>
                <td><input type="text" id="cat_desc" /></td>
            </tr>
        </table>
    </div>
    
    <div id="new_subcategory" title="席次表用区分詳細新規" >
    	<table width="80%" cellpadding="3" cellspacing="0" border="0" align="center">
        	<tr>
            	<td>席次表用区分詳細</td>
                <td><input type="text" id="subcat_name" /></td>
            </tr>

        </table>
    </div>
</div>
 <?php
include("inc/new.footer.inc.php");
?>
