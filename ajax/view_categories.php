<?php
	require_once("../admin/inc/class.dbo.php");

	$obj = new DBO();
	$categories = $obj->GetAllRowsByCondition(" spssp_guest_category "," user_id=".(int)$_SESSION['userid']." order by display_order desc ");
	$post = $obj->protectXSS($_POST);
	
	if(isset($_POST['view_cats']) && $_POST['view_cats'] != '')
	{
	?>
    	<p class="txt3" style="width:80%; margin:0 auto;"><a href="javascript:void(0);" onclick="add_category()">区分新規登録</a></p>
    	<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" class="data">
        	<?php
				$i=1;
				foreach($categories as $cat)
				{
				if($i%2 != 0)
				{
					$stl = "background-color:#EDEDED";	
				}
				else
				{
					$stl='';
				}
			?>
            		<tr style="<?=$stl?>">
                    	 <td width="10%"><span id="<?php echo $i;?>"><?php echo $i;?></span></td>
                    	<td><a href="javascript:void(0);" onclick="view_sub_cats(<?=$cat['id']?>)"><?=$cat['name']?></a></td>
                        <td><?=$cat['description']?></td>
                        <td><a href="javascript:void(0)" onclick="edit_cat(<?=$cat['id']?>,'<?=$cat['name']?>','<?=$cat['description']?>')">編集</a></td>
                        <td><a href="javascript:void(0)" onclick="delete_cat(<?=$cat['id']?>)">削除</a></td>
                    </tr>
            <?php	
				$i++;
				}
			?>
        </table>
    <?php
		exit;
	}
	
	if(isset($_POST['view_sub_cats']) && $_POST['view_sub_cats'] != '')
	{
		$catid = (int)$_POST['catid'];
		$catname = $obj->GetSingleData("spssp_guest_category", "name"," id=".$catid);
		$sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".(int)$_SESSION['userid']." and category_id = $catid ");
		?>
			<p class="txt3" style="width:80%; margin:0 auto; text-align:center;background-color:#ECF4FB;">
            	<b><a href="javascript:void(0);" onclick="view_cats()"><?=$catname?></a>&nbsp;&nbsp;>>席次表用区分詳細</b>
                        <input type="hidden" id="category_id" value="<?=$catid?>" />
            </p>
            <p class="txt3" style="width:80%; margin:0 auto;"><a href="javascript:void(0);" onclick="add_subcategory()">席次表用区分詳細新規</a></p>
			<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" class="data">

				<?php
					$i=1;
					foreach($sub_categories as $sub_cat)
					{
					if($i%2 != 0)
					{
						$stl = "background-color:#EDEDED";	
					}
					else
					{
						$stl='';
					}
				?>
						<tr style="<?=$stl?>">
							<td width="10%"><span id="<?php echo $i;?>"><?php echo $i;?></span></td>
							<td><?=$sub_cat['name']?></td>
                            <td><a href="javascript:void(0)" onclick="edit_subcat(<?=$sub_cat['id']?>,'<?=$sub_cat['name']?>')">編集</a></td>
                        	<td><a href="javascript:void(0)" onclick="delete_subcat(<?=$sub_cat['id']?>)">削除</a></td>
							
						</tr>
				<?php	
					$i++;
					}
				?>
			</table>
		<?php
		exit;
	}
	
	if(isset($_POST['new_cat']) && $_POST['new_cat'] != '')
	{
		$arr['name']= trim($post['cat_name']);
		$arr['description']= trim($post['cat_desc']);
		$arr['user_id']= (int)$_SESSION['userid'];
		$arr['display_order']= time();
		$arr['creation_date']= date("Y-m-d H:i:s");
		$catid = (int)$_POST['catid'];
		
		if($catid == 0)
		{
			$id = $obj->InsertData(" spssp_guest_category ", $arr);
			
			echo $id;
		}
		else
		{
			unset($arr['user_id']);
			unset($arr['display_order']);
			unset($arr['creation_date']);
			$obj->UpdateData("spssp_guest_category", $arr, " id=".$catid);
			
			echo $catid;
		}
		
		exit;
	}
	
	if(isset($_POST['delete_cat']) && $_POST['delete_cat'] != '')
	{
		$catid = (int)$_POST['catid'];
		$sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".(int)$_SESSION['userid']." and category_id = $catid ");
		if(!empty($sub_categories))
		{
			foreach($sub_categories as $scat)
			{
				$scatid_arr[]=$scat['id'];
			}
			if(!empty($scatid_arr))
			{
				$subcat_id_string = implode(",",$scatid_arr);
				$obj->DeleteRow("spssp_guest", " sub_category_id in ( $subcat_id_string )");
				
			}
			
			$obj->DeleteRow("spssp_guest_sub_category", " category_id=".$catid);
			
		}
		$obj->DeleteRow("spssp_guest_category", " id=".$catid);
		echo $catid;
		exit;
	}
	
	if(isset($_POST['new_subcat']) && $_POST['new_subcat'] != '')
	{
		$arr['name']= trim($post['subcat_name']);
		$arr['category_id']= $post['catid'];
		$arr['user_id']= (int)$_SESSION['userid'];
		$arr['display_order']= time();
		$arr['creation_date']= date("Y-m-d H:i:s");
		$catid = (int)$_POST['catid'];
		
		$subcat_id = (int)$post['subcat_id'];
		if($subcat_id <= 0)
		{
			$id = $obj->InsertData(" spssp_guest_sub_category ", $arr);
			
			echo $id;
		}
		else
		{
			unset($arr['user_id']);
			unset($arr['display_order']);
			unset($arr['creation_date']);
			$obj->UpdateData("spssp_guest_sub_category", $arr, " id=".$subcat_id);
			
			echo $subcat_id;
		}
		
		exit;
	}
	
	if(isset($post['load_new_subcat']) && $post['load_new_subcat'] != '')
	{
		$sub_categories = $obj->GetAllRowsByCondition(" spssp_guest_sub_category "," user_id=".(int)$_SESSION['userid']." and category_id=".(int)$post['cat_id']);
		if(!empty($sub_categories))
		{
			foreach($sub_categories as $sub_category)
			{
				echo "<option value='".$sub_category['id']."'>".$sub_category['name']."</option>";
			}
		}
		exit;
	}
	
	if(isset($post['load_new_cat']) && $post['load_new_cat'] != '')
	{
		$categories = $obj->GetAllRowsByCondition(" spssp_guest_category "," user_id=".(int)$_SESSION['userid']);
		if(!empty($categories))
		{
			foreach($categories as $cat)
			{
				echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
			}
		}
		exit;
	}
	
	
	if(isset($_POST['delete_subcat']) && $_POST['delete_subcat'] != '')
	{
		$subcatid = (int)$_POST['id'];
	
		$obj->DeleteRow("spssp_guest", " sub_category_id = $subcatid");
		
		$obj->DeleteRow("spssp_guest_sub_category", " id=".$subcatid);

		echo $subcatid;
		exit;
	}
	
	if(isset($_POST['accept_default']) && $_POST['accept_default'] != '')
	{
		
		$did = (int)$post['did'];
		$default_cat_row = $obj->GetSingleRow("spssp_guest_category", " id=".(int)$did);
		$default_cat_row['user_id'] = (int)$_SESSION['userid'];
		$default_cat_row['default_cat_id'] = $did;
		$default_cat_row['display_order'] = time();
		$default_cat_row['creation_date'] = date("Y-m-d H:i:s");
		unset($default_cat_row['id']);
		
		$id = $obj->InsertData(" spssp_guest_category ", $default_cat_row);
		if($id > 0)
		{		
			$sub_cats = $obj->GetAllRowsByCondition(" spssp_guest_sub_category ","  category_id=".(int)$did);
			if(!empty($sub_cats))
			{
				foreach($sub_cats as $sub_cat)
				{
					$sub_cat['category_id'] = $id;
					$sub_cat['default_sub_cat_id'] = $sub_cat['id'];
					$sub_cat['user_id']=(int)$_SESSION['userid'];
					$sub_cat['display_order']=time();
					$sub_cat['creation_date']=date("Y-m-d H:i:s");
					unset($sub_cat['id']);
				
					$lid = $obj->InsertData(" spssp_guest_sub_category ", $sub_cat);
				
				}
			}
		}
		
		echo $id;

		
		
		exit;
	}
	
	
?>
