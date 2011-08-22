<?php
include_once('dbcon.inc.php');
include_once("class.dbo.php");
class InformationClass extends DBO
{

	public function InformationClass()
	{

	}
	function get_admin_info($admin_id)
	{
		return $admin_info = $this->GetSingleRow("spssp_admin"," id=".$admin_id);
	}
	function get_printing_company_info($print_company_id)
	{
		return $print_company_info = $this->GetSingleRow("spssp_printing_comapny"," id=".$print_company_id);
	}
	function get_user_info($user_id)
	{
		return $user_info = $this->GetSingleRow("spssp_user"," id=".$user_id);
	}
	function get_user_respect_info($respect_id)
	{
		include("main_dbcon.inc.php");
		return $user_info = $this->GetSingleRow( "spssp_respect"," id=".$respect_id);
		include("return_dbcon.inc.php");
	}
	function get_user_staff_info($staff_id)
	{
		return $user_info = $this->GetSingleRow("spssp_admin"," id=".$staff_id);
	}
	function get_user_plan_info($user_id)
	{
		return $user_info = $this->GetSingleRow("spssp_plan"," user_id=".$user_id);
	}
	function get_date_with_supplyed_flag_difference( $date, $num_of_day, $flag=1 )
	{
		//$flag=1=plus.............$flag=2=minus

		$day = strftime('%d',strtotime($date));
		$month = strftime('%m',strtotime($date));
		$year = strftime('%Y',strtotime($date));

		if($flag==1)
			$lastmonth = mktime(0, 0, 0, $month, $day+$num_of_day, $year);
		if($flag==2)
			$lastmonth = mktime(0, 0, 0, $month, $day-$num_of_day, $year);

		$dateBeforeparty = date("Y/m/d",$lastmonth);

		return $dateBeforeparty;
	}

// UCHIDA EDIT 11/08/15 'dl_print_com_times'にpdfを参照を記録
	function pdf_readed($user_id, $dl, $v)
	{
		unset($post);
		$post['dl_print_com_times'] = $dl | $v;
		$this->UpdateData('spssp_plan',$post,"user_id=".$user_id);
	}

	function reset_guest_gift_page_and_user_orders_conditions($user_id,$flag=1)
	{
		unset($post);
		$post['msg_type']=0;
		$this->UpdateData('spssp_message',$post," msg_type=2 and user_id=".$user_id);

		unset($post);
		$post['order']=0;
		$this->UpdateData('spssp_plan',$post,"user_id=".$user_id);


		if($flag==1)
		{
			unset($post);
			$post['admin_to_pcompany']=0;
			$post['dl_print_com_times']=0;
			$post['ul_print_com_times']=0;
			$this->UpdateData('spssp_plan',$post,"user_id=".$user_id);
		}

		// UCHIDA EDIT 11/08/16 リセットでは席次印刷の時間のみ消去
		unset($post);
		$post['print_irai']=NULL;
		$post['print_ok']=NULL;
		$post['kari_hachu']=NULL;
		$post['hon_hachu']=NULL;
		$this->UpdateData('spssp_clicktime',$post,"user_id=".$user_id);
	}
	function get_user_id_md5($md5_user_id)
	{
		  $result =$this->getRowsByQuery("SELECT id from  spssp_user");

		  foreach($result as $row)
		  {
				if(md5($row['id'])==$md5_user_id)
				{
					$user_id = $row['id'];
				}
		  }
		  return $user_id;
	}
	function get_print_company_id_md5($md5_print_company)
	{
		$result = $this->getRowsByQuery("SELECT id from  spssp_printing_comapny");
		$md5_print_company = str_replace("/","",$md5_print_company);
		foreach($result as $row)
		{
			if(md5($row['id'])==$md5_print_company)
			{
				$print_company = $row['id'];
			}
		}
		 return $print_company;
	}
	function get_print_company_download_link($user_id , $printCompany_id )
	{
		$session_user = md5($user_id);
		$session_print = md5($printCompany_id);
		$link = PRINT_COMPANY_LINK."index.php?user_id=".$session_user."&page=download";//.$session_print;

		unset($post);
		$post['dl_print_com_times']=1;

		$this->UpdateData('spssp_plan',$post," user_id=".$user_id);
		return $link;
	}

	function get_print_company_upload_link($user_id , $printCompany_id )
	{
		$session_user = md5($user_id);
		$session_print = md5($printCompany_id);
		$link = PRINT_COMPANY_LINK."index.php?user_id=".$session_user."&page=upload";//.$session_print;

		unset($post);
		$post['ul_print_com_times']=1;

		$this->UpdateData('spssp_plan',$post," user_id=".$user_id);
		return $link;
	}
	function proccesse_gift_day_limit($user_id)
	{
		$user_row = $this->get_user_info($user_id);
		$gift_criteria = $this->GetSingleRow("spssp_gift_criteria", " id=1");
		$gift_criteria['order_deadline'];
		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $gift_criteria['order_deadline'] , $flag=2 );

		if($dateBeforeparty <= date("Y/m/d")) // 指定された期日以内になったか
		{
// UCHIDA EDIT 11/08/10 発注締切日はそのその状態を保持する
//			unset($post);
//			echo $post['gift_daylimit']=3;
//			$this->UpdateData('spssp_plan',$post," user_id=".$user_id);

			return true;
		}
		else
		{
			return false;
		}

	}
	function proccesse_gift_day_limit_7_days($user_id)
	{
		$user_row = $this->get_user_info($user_id);
		$gift_criteria = $this->GetSingleRow("spssp_gift_criteria", " id=1");
		$gift_criteria['order_deadline'];
		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_row['party_day'] , 7 , $flag=2 );

		if($dateBeforeparty <= date("Y/m/d")) // 指定された期日以内になったか 8/17なら8/10を過ぎたか
		{
// UCHIDA EDIT 11/08/10 ７日締切日はそのその状態を保持する
//			unset($post);
//			echo $post['gift_daylimit']=3;
//			$this->UpdateData('spssp_plan',$post," user_id=".$user_id);

			return true;
		}
		else
		{
			return false;
		}

	}

	function sekiji_day_limit_over_check_for_all_users($user_id)
	{
		$user_info = $this->get_user_info($user_id);
		$confirm_day_num = $this->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");

		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_info['party_day'] , $confirm_day_num , $flag=2 );
		//echo "<br>";
		$partydate = str_replace("-","/",$user_info['party_day']);
		//echo "<br>";
		//echo date("Y/m/d");


		if($dateBeforeparty <= date("Y/m/d") && date("Y/m/d")< $partydate)
		{

			return true;
		}
		else
		{
			return false;
		}
	}
	function sekiji_user_day_over_email_send_for_today_check($user_id)
	{
		$check_value = $this->GetSingleData("spssp_plan" ,"sekiji_email_send_today_check" ," user_id=".$user_id);

		if($check_value < date("Y/m/d"))
		{
			$post['sekiji_email_send_today_check'] = date("Y/m/d");
			$this->UpdateData('spssp_plan',$post," user_id=".$user_id);
			return true;
		}
		else
			return false;
	}
	function sekiji_day_limit_over_check_for_7days_all_users($user_id)
	{
		$user_info = $this->get_user_info($user_id);
		$confirm_day_num = $this->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");

		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_info['party_day'] , 7 , $flag=2 );
		$partydate = str_replace("-","/",$user_info['party_day']);
		if($dateBeforeparty <= date("Y/m/d") && date("Y/m/d")< $partydate)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function get_editable_condition($plan_info_array)
	{
		if(($plan_info_array['order']<=3 && $plan_info_array['order']>0) && ($_SESSION['userid']!=$_SESSION['userid_admin'] || ($plan_info_array['order']==2 && $plan_info_array['admin_to_pcompany']==3)))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function get_image_db_directory($hotel_id){
        $result_image_db_dir = "";
        $query = "select gc_sval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 3 and gc_cscode = ".$hotel_id;
        $result = mysql_query($query );
        $num = mysql_num_rows($result);
        if($num>0){
            while($fetchedRow = mysql_fetch_assoc($result)){
                $result_image_db_dir = (string)$fetchedRow["val"];
            }
        }
        mysql_free_result($result);
        //
        return $result_image_db_dir;
    }
  function get_user_name_image_or_src( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false,$height = false )
	{
		 $file = sprintf("%s/user_name/%d/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$name);

		 if($extra)
		 $file = sprintf("%s/user_name/%d/%s/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$extra,$name);

		if(is_file($file))
		{
			if($opt == "src")
				return $file;
			else if($extra)
			{

			return "<img src=\"".$file."\" />";
			}else if($height){
        $file = str_replace("../","",$file);
				return "<img src='../image.php?f=".$file."&h=".$height."' />";
      }
			else
			{
				$file = str_replace("../","",$file);
				return "<img src='../image.php?f=".$file."&w=".$width."' />";
			}
		}
		else
		{
			return false;
		}
	}
function get_user_name_image_or_src_from_user_side( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false )
	{
		 $file = sprintf("%s/user_name/%d/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$name);

		 if($extra)
		 $file = sprintf("%s/user_name/%d/%s/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$extra,$name);

		 $file = str_replace("../","",$file);

		 if(is_file($file))
		 {
			if($opt == "src")
				return $file;
			else if($extra)
			{

				return "<img src=\"".$file."\" />";
			}
			else
			{
				return "<img src='image.php?f=".$file."&w=".$width."' />";
			}
		}
		else
		{
			return false;
		}
	}
	function get_user_name_image_or_src_from_ajax( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false )
	{
		 $file = sprintf("%s/user_name/%d/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$name);

		 if($extra)
		 $file = sprintf("%s/user_name/%d/%s/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$extra,$name);

		 $file1 = str_replace("../","../../",$file);

		 if(is_file($file1))
		 {
			if($opt == "src")
				return $file;
			else if($extra)
			{

				return "<img src=\"".$file."\" />";
			}
			else
			{
				return "<img src='image.php?f=".$file."&w=".$width."' />";
			}
		}
		else
		{
			return false;
		}
	}

function get_user_name_image_or_src_from_user_side_make_plan( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false )
	{
		 $file = sprintf("%s/user_name/%d/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$name);

		 if($extra)
		 $file = sprintf("%s/user_name/%d/%s/%s",$this :: get_image_db_directory($hotel_id),(int)$user_id,$extra,$name);

		 $file = str_replace("../","",$file);

		 if(is_file($file))
		 {
			if($opt == "src")
				return $file;
			else if($extra)
			{

				return "<img src='".$file."' />";
			}
			else
			{
				return "<img src='image.php?f=".$file."&w=".$width."' />";
			}
		}
		else
		{
			return false;
		}
	}

// UCHIDA EDIT 11/08/16
	function get_clicktime_info($user_id)
	{
		return $clicktime_info = $this->GetSingleRow("spssp_clicktime"," user_id=".$user_id);
	}

	function update_clicktime_info($type, $dt, $user_id)
	{
		unset($post);
		switch ($type) {
	    case "print_irai":
			$post['print_irai']=$dt;
	    	break;
	    case "print_ok":
			$post['print_ok']=$dt;
	    	break;
	    case "hikide_irai":
			$post['hikide_irai']=$dt;
	    case "kari_hachu":
			$post['kari_hachu']=$dt;
	    	break;
	    case "hon_hachu":
			$post['hon_hachu']=$dt;
	    	break;
	    case "hikide_zumi":
			$post['hikide_zumi']=$dt;
	        break;
		default:
	    	return "ERROR";
		}

		$this->UpdateData('spssp_clicktime',$post,"user_id=".$user_id);
	}
}//END OF CLASS_InformationClass
?>
