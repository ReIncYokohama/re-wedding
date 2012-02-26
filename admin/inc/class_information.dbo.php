<?php
include_once(dirname(__file__).'/dbcon.inc.php');
include_once(dirname(__file__)."/class.dbo.php");
include_once(dirname(__file__)."/../../fuel/load_classes.php");
class InformationClass extends DBO
{
	function get_admin_info($admin_id)
	{
    return Model_Admin::find_by_pk($admin_id);
	}
	function get_printing_company_info($print_company_id)
	{
		return $print_company_info = $this->GetSingleRow("spssp_printing_comapny"," id=".$print_company_id);
	}
	function get_room_info($room_id)
	{
		return $room_info = $this->GetSingleRow("spssp_room"," id=".$room_id);
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
	function pdf_readed($user_id, $v)
	{
		unset($post);
		$post['dl_print_com_times'] = $v;
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
			$post['sekiji_email_send_today_check'] = ""; // UCHIDA EDIT 11/08/17 メールの送信もクリア
			$this->UpdateData('spssp_plan',$post,"user_id=".$user_id);
		}

		// UCHIDA EDIT 11/08/16 リセットでは席次印刷の時間のみ消去
		unset($post);
		//$post['print_irai']=NULL;
		//$post['print_ok']=NULL;
		//$post['kari_hachu']=NULL;
		//$post['hon_hachu']=NULL;
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
//		$gift_criteria = $this->GetSingleRow("spssp_gift_criteria", " id=1");
//		$gift_criteria['order_deadline'];
		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $user_row['order_deadline'] , $flag=2 );
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
//		$gift_criteria = $this->GetSingleRow("spssp_gift_criteria", " id=1");
//		$gift_criteria['order_deadline'];
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
//		$confirm_day_num = $this->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");

		$dateBeforeparty = $this->get_date_with_supplyed_flag_difference( $user_info['party_day'] , $user_info['confirm_day_num'] , $flag=2 );

// UCHIDA EDIT 11/08/17 締切日だけの確認
//		$partydate = str_replace("-","/",$user_info['party_day']);
//		if($dateBeforeparty <= date("Y/m/d") && date("Y/m/d")< $partydate)
// echo "<script> alert('$dateBeforeparty'); </script>";
		if($dateBeforeparty <= date("Y/m/d"))
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

// UCHIDA EDIT 11/08/17 メール送信を記録
//		if($check_value < date("Y/m/d"))
		if($check_value == "")
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
//		$confirm_day_num = $this->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");

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
  //席次表編集期間を過ぎているかどうか判定
  //old function::you must use this function (fuel/app/model/user past_deadline_sekijihyo)
  function get_sekizihyo_edit_term($plan_arr){
    return Model_User::past_deadline_sekijihyo($plan_arr["user_id"]);
  }
	function get_editable_condition($plan_arr)
	{
    $plan = Model_Plan::find_one_by_user_id($plan_arr["user_id"]);
    return $plan->editable();
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
    if($extra==".") $extra = "";
    if($extra!="") $extra .= "/";
    $file = Core_Image::get_user_image_dir_relative($user_id).$extra.$name;
		if(is_file($file))
		{
			if($opt == "src")
				return $file;
			else if($extra!==false)
			{
        if($height){
          return "<img src=\"".$file."\" height=\"".$height."\" />";
        }
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
  function get_user_name_image_or_src_from_user_side( $user_id ,$hotel_id , $name ,$extra="",$width , $opt = false,$height )
	{
    return $this->get_user_name_image_or_src($user_id,$hotel_id,$name,$extra,$width,$opt,$height);
	}

	function get_user_name_image_or_src_from_ajax( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false )
	{
    return $this->get_user_name_image_or_src($user_id,$hotel_id,$name,$extra,$width,$opt);
	}

function get_user_name_image_or_src_from_user_side_make_plan( $user_id ,$hotel_id , $name ,$extra="",$width = 100 , $opt = false )
	{
    return $this->get_user_name_image_or_src($user_id,$hotel_id,$name,$extra,$width,$opt=true);
	}

	function get_clicktime_info($user_id)
	{
    return Model_Clicktime::find_by_user_id($this->user_id);
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
// UCHIDA EDIT 11/09/02
	function get_table_name($id) {
		$sql = "SELECT * FROM spssp_tables_name where id = ".$id.";"; // name=id
		$data_rows=mysql_query($sql);
		$row=mysql_fetch_array($data_rows);
		return $row['name'];
	}
	function get_table_id($name) {
		$sql = "SELECT * FROM spssp_tables_name where name = '".$name."';"; // name=id
		$data_rows=mysql_query($sql);
		$row=mysql_fetch_array($data_rows);
		return $row['id'];
	}


/*
*title
招待客のテーブル情報を表示する際に利用する
*arguments
user_id userのid
*return
json
["lines"][0]["rows"][0]["guest_id_arr"][]  {1,"","","",3,""} ->guest_idの配列
                       ["table_name"] {テーブル名}
                       ["display"] {0,1,2} 0 none 1 visibility 2 block 表示
            ["align"]  {N,L,C}
            ["row_num"]     display:nonwのテーブルを含まない
            ["max_row_num"] display:noneのテーブルも含む
["takasago"] {1,""} ->guest_idの配列
*memo

*/
  function get_table_info($user_id){
    $tblrows = $this->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
    $num_tables = $this->getSingleData("spssp_plan", "column_number"," user_id= $user_id");

    $plan_id = $this->GetSingleData("spssp_plan", "id","user_id=".$user_id);
    $plan_row = $this->GetSingleRow("spssp_plan", " id =".$plan_id);
    $seats_num = $plan_row["seat_number"];

    $i=0;
    $returnArray = array("lines"=>array(),"takasago" => array());
    foreach($tblrows as $tblrow)
      {
        $lineObject = array("align" => "","rows" => array());
        $i++;
        
        $ralign = $this->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");
        $lineObject["align"] = $ralign;
        $num_hidden_table = $this->GetNumRows("spssp_table_layout","user_id = $user_id and display = 0 and row_order=".$tblrow['row_order']);

        $num_first = $this->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order limit 1");
        $num_last = $this->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
        $num_max = $this->GetSingleData("spssp_table_layout", "column_order "," user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
        $num_none = $num_max-$num_last+$num_first-1;
        
        $lineObject["max_row_num"] = $num_max;
        $lineObject["row_num"] = $num_max - $num_none;
        
        $table_rows = $this->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
        foreach($table_rows as $table_row)
          {
            $rowObject = array("guest_id_arr" => array(),"display" => "", "table_name" => "");
            $new_name_row = $this->GetSingleRow("spssp_user_table", " user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
            
            $tblname='';
            if($table_row['name']!='')
              {
                $tblname = $table_row['name'];
              }
            elseif(is_array($new_name_row) && $new_name_row['id'] !='')
              {
                
                $tblname_row = $this->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
                $tblname = $tblname_row['name'];
              }
            $rowObject["table_name"] = $tblname;
            
            if($table_row["display"] == 1){
              $rowObject["display"] = 2;
            }else if(($num_first <= $table_row["column_order"] && $table_row["column_order"]<=$num_last) || $ralign == "N" ){
              $rowObject["display"] = 1;
            }else{
              $rowObject["display"] = 0;
            }
            
            $seats = $this->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$seats_num");

            foreach($seats as $seat)
              {

                $guest_id = $this->GetSingleData("spssp_plan_details","guest_id"," seat_id=".$seat["id"]." and plan_id =".$plan_id);                
                if($guest_id){
                  array_push($rowObject["guest_id_arr"],$guest_id);
                }else{
                  array_push($rowObject["guest_id_arr"],"");
                }
              }
            array_push($lineObject["rows"],$rowObject);
          }
        array_push($returnArray["lines"],$lineObject);
      }
    return $returnArray;
  }

  
  public function delete_user_relation_table($uid) {
	  $_plan = $this->GetSingleRow("spssp_plan", "user_id=".$uid);
	   
	  $sql = "delete from spssp_plan_details where plan_id=".(int)$_plan['id'];	mysql_query($sql);
	  $sql = "delete from guest_csv_upload_log where user_id=".$uid;				mysql_query($sql);
	  $sql = "delete from spssp_admin_messages where user_id=".$uid; 				mysql_query($sql);
	  $sql = "delete from spssp_change_log where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_clicktime where user_id=".$uid; 					mysql_query($sql);
	  
	  $sql = "delete spssp_item_value from spssp_item_value inner join spssp_gift on spssp_item_value.item_id = spssp_gift.id where spssp_gift.user_id=".$uid; mysql_query($sql);	  
	  $sql = "delete from spssp_gift where user_id=".$uid; 						mysql_query($sql);

	  $sql = "delete from spssp_gift_group where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_gift_group_relation where user_id=".$uid; 		mysql_query($sql);
	  $sql = "delete from spssp_guest where user_id=".$uid; 						mysql_query($sql);
	  $sql = "delete from spssp_guest_gift where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_guest_menu where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_guest_orderstatus where user_id=".$uid; 			mysql_query($sql);
	  $sql = "delete from spssp_menu_group where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_message where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_plan where user_id=".$uid; 						mysql_query($sql);
	  $sql = "delete from spssp_table_layout where user_id=".$uid; 				mysql_query($sql);
	  $sql = "delete from spssp_user_log where user_id=".$uid; 					mysql_query($sql);
	  $sql = "delete from spssp_user_table where user_id=".$uid; 					mysql_query($sql);
	    	
	  $sql = "delete from spssp_user where id=".$uid;								mysql_query($sql);
  }

	public function is_super_user(){
		if($_SESSION["super_user"] == true){
			return true;
		}else{
			return false;
		}
	}
	
	public function is_admin(){
		if((int)$_SESSION["adminid"] > 0){
			return true;
		}else{
			return false;
		}
	}

}//END OF CLASS_InformationClass
?>
