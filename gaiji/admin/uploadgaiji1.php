<?php
    include_once("inc/dbcon.inc.php");
	@session_start();
	include_once("inc/checklogin.inc.php");
	include_once("inc/textarray.inc.php");

    
	$basepath='./tmp/';
	@mkdir($basepath);
	
	$db_table_csv = "gaiji_buso";
	
	$array_property=array();
	$array_address=array();
	
	$query_string="SELECT property_no,name_address1,location_names2,location_name3,prices,along_the_short1 FROM ".$db_table_csv.";";            
    $db_result=mysql_query($query_string);
	if(mysql_num_rows($db_result))
      {
	  	while($propert_row=mysql_fetch_array($db_result))
		{
			$array_property[]=$propert_row['property_no'];
			$array_address[]= jp_decode($propert_row['name_address1']).','.jp_decode($propert_row['location_names2']).','.jp_decode($propert_row['location_name3']).','.$propert_row['prices'].','.jp_decode($propert_row['along_the_short1']);
		}	
	  }
	

	if(isset($_POST['csvfile']))
	{
		ini_set('upload_max_filesize','20M');
		ini_set('post_max_size','20M');
		ini_set('max_execution_time','1200');
		ini_set('max_input_time','200');
		
		
		$displayorder = time();
		
		$time = time();
		move_uploaded_file($_FILES['csvfile']['tmp_name'], $basepath.$time.'.csv');
		
		
		$lines = @file($basepath.$time.".csv");

		$line0 = @array_shift($lines);
		//$line0 = str_replace("\"","",$line0);
		$line = explode(",",$line0);
		
		//dumpvar($line);exit;		
		$lnum = count($lines);
		$fieldarray = 
array('property_no','data_types','property_type','property_event','name_members','phone_representatives','contact_person1','contact_telephone_number1','email_address1','drawings','date_of_registration','date_change','expiration_of_terms_and_conditions','newly_owned_division','state_name','name_address1','location_names2','location_name3','building_name','room_number','view_other_locations','building_number','along_the_short1','station_name1','walking_minutes1_1','distance_m_2_1','bus1','bus_name1','bus_stop_name1','stop_walking_minutes1','stopping_step_m1','car_km_1','other_transportation','traffic_minutes1','traffic','current_state','present_plan_years','delivery_time','delivery_month_ad','late_delivery','month_occupancy_ad','occupancy_date','trade_aspects4','form_of_remuneration','percentage_rate_of_charge','commission','prices','tax_price','per_tsubo','per_square','expected_yield','area_measurement_system','land_area','land_area_of_shared_interest','land_co_ownership_interest_numerator','land_equity_shares_denominator','building_area1','footprint','driveway_or_without_pay','driveway_area','balcony_terrace_area','private_garden_area','groups_setback','setback_distance_m','setback_area_sqm','development_area_total_area','the_total_area_for_sale','number_of_parcels_for_sale','completion_of_construction_years_ad','construction_area','total_area','whether_the_extension_site','masaru_nobu_site','rent','tenure','lease_period_ad','facility_expense1','facility_costs1','notification_of_land_act','category_of_land_registry','present_land_category','urban_planning','zoning1','zoning2','optimal_use','building_coverage','floor_area_ratio','regional_district','land_rights','ancillary_rights','fri_trouble_wataru_Yuzuru','fri_constant_borrowing_rights','fixed_deposit_loans','fixed_deposit_loans2','terrain','building_condition','owner_change','whether_management_association','administration_forms','management_company','state_manager','administration_expenses','spending_tax_administration_fee','repair_reserve_funds','name_one_other_monthly_expenses','a_monthly_cost_and_other_amounts','owner','construction_company','sale_company','bulk_subcontractor','connecting_road_conditions','type_a_street_connection','a_road_connecting_the_contact_surface','locating_a_connecting_road','connect_one_way_street','roads_connecting_member1','type2_road_connections','road_connecting_two_contact_surfaces','road_connecting_two_positional','two_way_street_connecting','roads_connecting_two_members','type3_road_connections','road_connecting_the_contact_surface3','positioned_three_road_connections','three_way_connection_road','roads_connecting_member3','type4_road_connections','road_connecting_the_contact_surface4','positioned_four_connectivity_road','four_way_directional_connection','roads_connecting_member4','paved_road_connecting','take_between_type1','number_of_rooms_taken_between1','room_location','of_closet','located_one_floor_room','room_type_1','one_room_wide','one_rooms','located_on_the_second_floor_room','room_type21','the_two_large_rooms1','two_rooms1','level3_room_located1','room_type31','the_three_large_rooms1','three_rooms1','chamber_located_four_floors1','room_type41','the_four_large_rooms1','four_rooms1','located_in_the_fifth_floor_room1','room_type51','the_five_large_rooms1','five_rooms1','office_location_level61','room_type61','the_six_large_rooms','six_rooms1','office_location_level71','room_type71','the_seven_large_rooms1','seven_rooms1','other_floor_plans1','currently_no_parking','monthly_parking','monthly_parking_tax','parking_deposit_amount','parking_deposit_months','parking_key_money_amount','parking_key_money_months','building_structure','building_construction','building_form','ground_layer_20a','ground_layer','floor_location_20b','month_built_year15','total_number_of_units','total_number_of_units_buildings','the_number_of_units_relevant_building','balcony_direction1','one_month_renovation','a_history_of_remodeling','remodeling_two_years','history_2_renovation','remodeled_3years','three_Historical_renovation','ambience_1free','a_distance','one_hour','access_to_a_nearby','two_one_note1','note2','management_field_house','no_flags_reconstruction');
		
		for($i=0;$i<$lnum;$i++)
		{

			$lines[$i] = str_replace("\"","",$lines[$i]);
			$lines[$i] = chop($lines[$i]);
	        
			list($property_no,$data_types,$property_type,$property_event,$name_members,$phone_representatives,$contact_person1,$contact_telephone_number1,$email_address1,$drawings,$date_of_registration,$date_change,$expiration_of_terms_and_conditions,$newly_owned_division,$state_name,$name_address1,$location_names2,$location_name3,$building_name,$room_number,$view_other_locations,$building_number,$along_the_short1,$station_name1,$walking_minutes1_1,$distance_m_2_1,$bus1,$bus_name1,$bus_stop_name1,$stop_walking_minutes1,$stopping_step_m1,$car_km_1,$other_transportation,$traffic_minutes1,$traffic,$current_state,$present_plan_years,$delivery_time,$delivery_month_ad,$late_delivery,$month_occupancy_ad,$occupancy_date,$trade_aspects4,$form_of_remuneration,$percentage_rate_of_charge,$commission,$prices,$tax_price,$per_tsubo,$per_square,$expected_yield,$area_measurement_system,$land_area,$land_area_of_shared_interest,$land_co_ownership_interest_numerator,$land_equity_shares_denominator,$building_area1,$footprint,$driveway_or_without_pay,$driveway_area,$balcony_terrace_area,$private_garden_area,$groups_setback,$setback_distance_m,$setback_area_sqm,$development_area_total_area,$the_total_area_for_sale,$number_of_parcels_for_sale,$completion_of_construction_years_ad,$construction_area,$total_area,$whether_the_extension_site,$masaru_nobu_site,$rent,$tenure,$lease_period_ad,$facility_expense1,$facility_costs1,$notification_of_land_act,$category_of_land_registry,$present_land_category,$urban_planning,$zoning1,$zoning2,$optimal_use,$building_coverage,$floor_area_ratio,$regional_district,$land_rights,$ancillary_rights,$fri_trouble_wataru_Yuzuru,$fri_constant_borrowing_rights,$fixed_deposit_loans,$fixed_deposit_loans2,$terrain,$building_condition,$owner_change,$whether_management_association,$administration_forms,$management_company,$state_manager,$administration_expenses,$spending_tax_administration_fee,$repair_reserve_funds,$name_one_other_monthly_expenses,$a_monthly_cost_and_other_amounts,$owner,$construction_company,$sale_company,$bulk_subcontractor,$connecting_road_conditions,$type_a_street_connection,$a_road_connecting_the_contact_surface,$locating_a_connecting_road,$connect_one_way_street,$roads_connecting_member1,$type2_road_connections,$road_connecting_two_contact_surfaces,$road_connecting_two_positional,$two_way_street_connecting,$roads_connecting_two_members,$type3_road_connections,$road_connecting_the_contact_surface3,$positioned_three_road_connections,$three_way_connection_road,$roads_connecting_member3,$type4_road_connections,$road_connecting_the_contact_surface4,$positioned_four_connectivity_road,$four_way_directional_connection,$roads_connecting_member4,$paved_road_connecting,$take_between_type1,$number_of_rooms_taken_between1,$room_location,$of_closet,$located_one_floor_room,$room_type_1,$one_room_wide,$one_rooms,$located_on_the_second_floor_room,$room_type21,$the_two_large_rooms1,$two_rooms1,$level3_room_located1,$room_type31,$the_three_large_rooms1,$three_rooms1,$chamber_located_four_floors1,$room_type41,$the_four_large_rooms1,$four_rooms1,$located_in_the_fifth_floor_room1,$room_type51,$the_five_large_rooms1,$five_rooms1,$office_location_level61,$room_type61,$the_six_large_rooms,$six_rooms1,$office_location_level71,$room_type71,$the_seven_large_rooms1,$seven_rooms1,$other_floor_plans1,$currently_no_parking,$monthly_parking,$monthly_parking_tax,$parking_deposit_amount,$parking_deposit_months,$parking_key_money_amount,$parking_key_money_months,$building_structure,$building_construction,$building_form,$ground_layer_20a,$ground_layer,$floor_location_20b,$month_built_year15,$total_number_of_units,$total_number_of_units_buildings,$the_number_of_units_relevant_building,$balcony_direction1,$one_month_renovation,$a_history_of_remodeling,$remodeling_two_years,$history_2_renovation,$remodeled_3years,$three_Historical_renovation,$ambience_1free,$a_distance,$one_hour,$access_to_a_nearby,$two_one_note1,$note2,$management_field_house,$no_flags_reconstruction) = explode(",",$lines[$i]);
			    
			$displayorder = $displayorder+$i;  
			 
		
			$string5=trim(trim(jp_encode3($name_address1)),"　").','.trim(trim(jp_encode3($location_names2)),"　").','.trim(trim(jp_encode3($location_name3)),"　").','.trim(trim($prices),"　").','.trim(trim(jp_encode3($along_the_short1)),"　");            
            $namemembers1 = mb_convert_encoding($name_members, "UTF8", "JIS, eucjp-win, sjis-win");
		    $namemembers2 = trim($namemembers1);
			
			
		
			$namemembers=trim($namemembers2,"　");
			
				
			if($property_no<1)
			{
				continue;
			}
			else if(in_array($namemembers,$array_text) || in_array($namemembers1,$array_text) || in_array($namemembers2,$array_text) || in_array($name_members,$array_text))
			{
				
				continue;
			
			}
			else
			{
				
			    if(in_array($property_no,$array_property))
				{
					$query='UPDATE deccs_concierge_bukken set ';
					for($j=0; $j<count($fieldarray);$j++)
					{
						$string = mb_convert_encoding($$fieldarray[$j], "UTF8", "JIS, eucjp-win, sjis-win");
						$fields = jp_encode1($$fieldarray[$j]);				   
						$query .= $fieldarray[$j] ."='".$string."',";
					}
					
					$lastupdate = "lastupdate='".date("Y-m-d")."'";
					$query .= $lastupdate." where property_no = '".$$fieldarray[0]."'";
					
					
				
				}
				else if(in_array($string5,$array_address))
				{			
					continue;
					//If need update
					/*$query='UPDATE deccs_concierge_bukken set ';
					for($j=0; $j<count($fieldarray);$j++)
					{
						$string = mb_convert_encoding($$fieldarray[$j], "UTF8", "JIS, eucjp-win, sjis-win");
						$fields = jp_encode1($$fieldarray[$j]);				   
						$query .= $fieldarray[$j] ."='".$string."',";
					}				
					$lastupdate = "lastupdate='".date("Y-m-d")."'";
					$query .= $lastupdate." where property_no = '".$$fieldarray[0]."'";*/
							
				}else{
				
					$query='insert into deccs_concierge_bukken set ';			
					for($j=0; $j<count($fieldarray);$j++)
					{
						$string = mb_convert_encoding($$fieldarray[$j], "UTF8", "JIS, eucjp-win, sjis-win");
						$query .= $fieldarray[$j] ."='".$string."',";
					}
					$lastupdate = "lastupdate='".date("Y-m-d")."',postdatetime='".date("Y-m-d H:i:s")."',displayorder='".$displayorder."'";			
					$query .= $lastupdate;
					
					$array_property[]=$property_no;
					$array_address[]= trim(trim(jp_encode3($name_address1)),"　").','.trim(trim(jp_encode3($location_names2)),"　").','.trim(trim(jp_encode3($location_name3)),"　").','.trim(trim($prices),"　").','.trim(trim(jp_encode3($along_the_short1)),"　"); 
					
				}
			
				mysql_query($query);
			}
		}
		
		//@unlink($basepath.$time.".csv");
		redirect($sslpath."admin2/bukken.php?page=".$_GET['page']);
	}
function jp_encode3($string)
{
  $string2 = mb_convert_encoding($string, "UTF8", "JIS, eucjp-win, sjis-win");
  return $string2;
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>横浜・川崎の不動産売買は神奈川不動産コンシェルジュ（デックス株式会社）にお任せください</title>
<meta name="description" content="神奈川,横浜,川崎,不動産,住宅情報,(新築・中古一戸建て,土地,マンション)をお届けします。">
<meta name="keywords" content="神奈川,横浜,川崎,不動産,神奈川,横浜市,川崎市,不動産情報,土地,一戸建て,新築,中古物件,マンション,デックス,deccs">
</head>

<body>
<div align="center">
  <p><strong>物件一括登録（CSV） </strong>≫<a href="<?=$sslpath?>admin2/manage.php">TOP</a></p>

  <hr noshade="noshade" />
  <div id="inp_area">
 <form method="post" enctype="multipart/form-data" action="<?=$sslpath?>admin2/uploadbukken.php">
		<table cellpadding="0" cellspacing="5" width="800">
			<? if((int)$_GET['message']){?>
			<tr>
				<td colspan="2" style="text-align:center;">物件の更新が完了しました</td>

			  </tr>
			<? }?>
			<tr>
				<td style="text-align:right;">物件データCSV</td>
				<td><input type="file" name="csvfile" /></td>
			  </tr>
			
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" name="csvfile" value="更新する"/> <input type="button" onClick="javascript:window.location='<?=$sslpath?>admin2/manage.php'" value="キャンセル"/></td>
			</tr>
		</table>
	</form>
<?php
		$query_string="SELECT count(id) AS total_record FROM deccs_concierge_bukken where status=1;";
		$db_result=mysql_query($query_string);
		if($db_row=mysql_fetch_array($db_result))
		{
			$total_record=$db_row['total_record'];
		}

		$total_record  = (int)$total_record;
		$data_per_page = 100;

		$total_page=ceil($total_record/$data_per_page);
		$current_page=(int)$_GET['page'];
		
		if($current_page>$total_page) $current_page=$total_page;
		if($current_page<=0) $current_page=1;

		if($current_page>=$total_page)
			$next=$current_page;
		else
			$next=$current_page+1;
		
		if($current_page<=0)
			$prev=0;
		else
			$prev=$current_page-1;
			
if($_GET['sort'] == '')
{
   $displayorder1 ='displayorder DESC';   
   $_GET['order'] = 'ASC'; 
}
else
{
     $displayorder1 = $_GET['sort'] ." ". $_GET['order'];
	 $_GET['order']= ($_GET['order'] == 'DESC')?"ASC":"DESC";   
}

if($total_page >1)
{
?>
<div >
<?php
	if($prev<$current_page&&$prev>0)
	{
		echo '|  <a href="uploadbukken.php?page='.$prev.'">&lt;&lt;前へ</a>';
	}
	
	if(($prev<$current_page&&$prev>0)||$next>$current_page)
	{
		echo ' | ';
	}
	
	if($next>$current_page)
	{
		echo '<a href="uploadbukken.php?page='.$next.'">次へ&gt;&gt;</a> |';
	}
?>
</div>
<? }?>
 <hr noshade="noshade" />
  <div id="inp_area">
  <script language="javascript">
	 var flag= true;
	function checkallfield()
	{
		var checkboxvalue = document.deleteAllrow.elements['checkfiled'];
		
		if(checkboxvalue.length >= 2)
		{
			if(flag)
			{     	
				for (var i=0; i<checkboxvalue.length; i++)
				{
				  checkboxvalue[i].checked = true;
				}
				flag=false;
			}
			else
			{
				flag=true;
			   for (var i=0; i<checkboxvalue.length; i++)
			   {
				  checkboxvalue[i].checked = false;
				}
			}
		}
		else
		{
			if(flag)
			{ 
				checkboxvalue.checked = true;				
				flag=false;
			}
			else
			{
				flag=true;			   
				checkboxvalue.checked = false;
				
			}
		}
	}

	function deleteAll()
	{
		var checkboxvalue = document.deleteAllrow.elements['checkfiled'];
		var flag = false;
		if(checkboxvalue.length)
		{			
			for (var i=0; i<checkboxvalue.length; i++)
			{
				if (checkboxvalue[i].checked)
				{
				   flag = true;
				}
			}
		}	
		else
		{
			if (checkboxvalue.checked)
			{
				flag = true;				
			}	
		}
		
		if(flag)
		{
		    if(confirm("削除します?"))
			{
		        document.deleteAllrow.submit();
			}
		}
		else
		{
		   alert("Please check one ");
		}
	}	
	</script>
  <form name="deleteAllrow" method="post" action="<?=$sslpath?>admin2/bukken_delete.php?cmd=all&page=<?=(int)$_GET['page']?>">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC" >
      <tbody>
        <tr bgcolor="#FFFFFF">
          <th bgcolor="#E8E8E8" ><input type="button" name="dell_bukken" value="削除" onclick="deleteAll()" /><input type="checkbox" onclick="checkallfield()"/></th>
            
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_event&order=<?=$_GET['order']?>">P公開</a></th>			
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=rank&order=<?=$_GET['order']?>">公開</a></th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=id&order=<?=$_GET['order']?>">ID</a>↓</th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_no&order=<?=$_GET['order']?>">物件番号</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=date_of_registration&order=<?=$_GET['order']?>">登録日</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=dealtype&order=<?=$_GET['order']?>">取引態様</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=disp&order=<?=$_GET['order']?>">表示</a></th>
            
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=property_type&order=<?=$_GET['order']?>">物件種別</a></th>
           <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=land_area&order=<?=$_GET['order']?>">土地面積</a></th>
		    <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=building_area1&order=<?=$_GET['order']?>">建物面積</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=prices&order=<?=$_GET['order']?>">価格</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=balcony_terrace_area&order=<?=$_GET['order']?>">ﾊﾞﾙｺﾆｰ面積</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=along_the_short1&order=<?=$_GET['order']?>">沿線</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=station_name1&order=<?=$_GET['order']?>">駅</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=name_address1&order=<?=$_GET['order']?>">所在地</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=location_names2&order=<?=$_GET['order']?>">所在地</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=bus_name1&order=<?=$_GET['order']?>">バス</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=walking_minutes1_1&order=<?=$_GET['order']?>">徒歩</a></th>

            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=month_built_year15&order=<?=$_GET['order']?>">築年月</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=take_between_type1&order=<?=$_GET['order']?>">部屋数</a></th>
			<th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=number_of_rooms_taken_between1&order=<?=$_GET['order']?>">タイプ</a></th>
          
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=building_name&order=<?=$_GET['order']?>">マンション名</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=name_members&order=<?=$_GET['order']?>">担当社名</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=contact_telephone_number1&order=<?=$_GET['order']?>">担当TEL</a></th>
            <th nowrap="nowrap" bgcolor="#E8E8E8"><a href="uploadbukken.php?sort=lastupdate&order=<?=$_GET['order']?>">最終更新日</a></th>

          <th width="100px" nowrap="nowrap" bgcolor="#E8E8E8">編集/Preview</th>         
        </tr>
            <?php
            $query_string="SELECT * FROM deccs_concierge_bukken where status='1'  ".$extra." ORDER BY $displayorder1  LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";            
			//echo $query_string;
            $db_result=mysql_query($query_string);
            if(mysql_num_rows($db_result))
            {
				while($db_row=mysql_fetch_array($db_result))
				{
				?>
				
				<tr bgcolor="#FFFFFF" >
				<td style="text-align:center; vertical-align:top;"><input type="checkbox" value="<?=$db_row['id']?>" name="checkfiled[]" id="checkfiled"/></td>
				<td valign="top"  ><input type="checkbox" <?=((int)$db_row['rank'] =='1')?"checked":"";?> id="pickup" name="pickup" onclick="changePickup(<?=$db_row['id']?>);"/></td>
				<td valign="top"  ><input type="checkbox" <?=((int)$db_row['property_event'] =='1')?"checked":"";?> id="member" name="member" onclick="changeMember(<?=$db_row['id']?>);"/></td>
				<td valign="top"  ><?=$db_row['id']?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['property_no'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['date_of_registration'])?></td>
                <td valign="top" style="text-align:left;">
				<?=($db_row['dealtype'] =='1')?"売主":"";?>
				<?=($db_row['dealtype'] =='2')?"代理":"";?>
				<?=($db_row['dealtype'] =='3')?"専属専任媒介":"";?>
				<?=($db_row['dealtype'] =='4')?"専任媒介":"";?>
				<?=($db_row['dealtype'] =='5')?"媒介":"";?>
				
				</td>
                <td valign="top" style="text-align:left;"><?=($db_row['property_event'] =='1')?"公　開":"非公開";?> </td>
                <td valign="top" style="text-align:left;">
				<?=($db_row['property_type'] =='1')?"土　地":"";?>
				<?=($db_row['property_type'] =='2')?"新築一戸建て":"";?>
				<?=($db_row['property_type'] =='3')?"中古一戸建て":"";?>
				<?=($db_row['property_type'] =='4')?"中古マンション":"";?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['land_area'])?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['building_area1'])?></td>
				<td valign="top" style="text-align:left;"><?=number_format(($db_row['prices']/10000),0,'.',',')?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['balcony_terrace_area'])?></td>
				<td valign="top" style="text-align:left;"><?=jp_decode($db_row['along_the_short1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['station_name1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['name_address1'])?><?=jp_decode($db_row['location_names2'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['location_names3'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['bus_name1'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['walking_minutes1_1'])?></td>
                <td valign="top" style="text-align:left;"><?
				
				$str1 = substr($db_row['month_built_year15'],0,-2);
	 $str2 = substr($db_row['month_built_year15'],-2);
	  echo $str1.'年'.$str2.'月'; 
				
				?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['take_between_type1'])?></td>
				 <td valign="top" style="text-align:left;">
						<?=($db_row['number_of_rooms_taken_between1'] =='1')?"R":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='2')?"K":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='3')?"DK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='4')?"LK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='5')?"LDK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='6')?"SK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='7')?"SDK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='8')?"SLK":""?>
						<?=($db_row['number_of_rooms_taken_between1'] =='9')?"SLDK":""?>
				 
				 </td>
				 
			    <td valign="top" style="text-align:left;"><?=jp_decode($db_row['building_name'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['name_members'])?></td>
                <td valign="top" style="text-align:left;"><?=jp_decode($db_row['contact_telephone_number1'])?></td>
                <td valign="top" style="text-align:left;"><?=strftime('%Y-%m-%d',strtotime($db_row['lastupdate']))?></td>
                <td valign="middle" style="text-align:center;"><a href="bukken_edit.php?id=<?=$db_row['id']?>">編集する</a><br><a href="preview.php?id=<?=$db_row['id']?>">Preview</a></td></tr>
             <? } }?>
      
    </table>
    </form>
  </div>
</div>
<br />



</body>
</html>
