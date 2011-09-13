<?php
include_once('dbcon.inc.php');

	class DBO
	{
		
		public function DBO()
		{
		
		}
		
		function protectXSS($array)
		{
			$new_array = array();
			if(is_array($array))
			{
				
				foreach($array as $key=>$value)
				{
					$val = $this->stripslashes2($value);
					//$val = strip_tags(htmlspecialchars(mysql_real_escape_string($vals),ENT_QUOTES,"UTF-8"));
					
					$new_array[$key] = $this->RemoveXSS($val);
				}
				
			}
			else if(is_string($array))
			{
				$val = strip_tags(htmlspecialchars(mysql_real_escape_string($array),ENT_QUOTES,"UTF-8"));
				$new_array[] = $this->RemoveXSS($val);
			}
			return $new_array;
		}
		
		
		function RemoveXSS($val)
		{
			
			$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
			
			// straight replacements, the user should never need these since they're normal characters
			// this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A &#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
			$search = 'abcdefghijklmnopqrstuvwxyz';
			$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$search .= '1234567890!@#$%^&*()';
			$search .= '~`";:?+/={}[]-_|\'\\';
			for ($i = 0; $i < strlen($search); $i++) {
				// ;? matches the ;, which is optional
				// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
			
				// &#x0040 @ search for the hex values
				$val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i],
					$val); // with a ;
				// &#00064 @ 0{0,7} matches '0' zero to seven times
				$val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
			}
			
			// now the only remaining whitespace attacks are \t, \n, and \r
			$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml',
				'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame',
				'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
			$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate',
				'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate',
				'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload',
				'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick',
				'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable',
				'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag',
				'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop',
				'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin',
				'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
				'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave',
				'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel',
				'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange',
				'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart',
				'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll',
				'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop',
				'onsubmit', 'onunload');
			$ra = array_merge($ra1, $ra2);
			
			
			$found = true; // keep replacing as long as the previous round replaced something
			while ($found == true) {
				$val_before = $val;
				for ($i = 0; $i < sizeof($ra); $i++) {
					$pattern = '/';
					for ($j = 0; $j < strlen($ra[$i]); $j++) {
						if ($j > 0) {
							$pattern .= '(';
							$pattern .= '(&#[xX]0{0,8}([9ab]);)';
							$pattern .= '|';
							$pattern .= '|(&#0{0,8}([9|10|13]);)';
							$pattern .= ')*';
						}
						$pattern .= $ra[$i][$j];
					}
					$pattern .= '/i';
					$replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
					$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
					if ($val_before == $val) {
						// no replacements were made, so exit the loop
						$found = false;
					}
				}
			}
			 return $val;
		}

		
		public function GetNumRows($tablename,$where)
		{
			$query = "select * from $tablename where ".$where;
			//echo $query;exit;
			$result = mysql_query($query);
			return mysql_num_rows($result);
		}
		public function GetRowCount($tablename,$where)
		{
			$fetchedRows = "";
			$query = "select * from $tablename where  ".$where;
			//echo $query;
			$result = mysql_query($query );
			$num = mysql_num_rows($result);
			if($num>0)
			{
				$fetchedRows=mysql_num_rows($result);
			}
			return $fetchedRows;
		}
		public function GetSingleData($tablename, $fieldname,$where)
		{
			$filedval = "";
			$query = "select $fieldname from $tablename where ".$where;
			
			$result = mysql_query($query );
			if($result)
			{
				$filedval = mysql_fetch_row($result);
				$filedval = $filedval[0];
			}
			return jp_decode($filedval);
		}
		
		public function GetFields($tablename, $fieldnames,$where)
		{
			$fetchedRows = "";
			$query = "select $fieldnames from $tablename where  ".$where;
			//echo $query;exit;
			$result = mysql_query($query );
			
			if($result)
			{
				while($row = mysql_fetch_assoc($result))
				{
					foreach($row as $key=>$value)
					{
						$row[$key]=stripslashes($value);
					}
					$fetchedRows[] = $row;
				}
			}
			return $fetchedRows;	
		}
		
		public function getRowsByQuery($qry)
		{
			$fetch_rows = array();
      $result = mysql_query($qry);
      if(!$result && DEBUG){
        echo "error:".mysql_error();
        exit();
			}
      $num = mysql_num_rows($result);
      
			if($num>0)
				{
				while($row = mysql_fetch_assoc($result))
				{
					foreach($row as $key=>$value)
					{
						$row[$key]=jp_decode(stripslashes($value));
					}
					$fetch_rows[] = $row;
				}
			}
			
			return $fetch_rows;
		}
		
		public function GetSingleRow($tablename, $where)
		{
			$fetchedRow = "";
			$query = "select * from $tablename where  ".$where;
			//echo $query;exit;
			$result = mysql_query($query );
      if(!$result) return;
			$num = mysql_num_rows($result);
			if($num>0)
			{
				$fetchedRow = mysql_fetch_assoc($result);
				
				foreach($fetchedRow as $key=>$value)
				{
					$fetchedRow[$key]=jp_decode(stripslashes($value));
				}
				
			}
			
			return $fetchedRow;
		}
		
		public function GetAllRow($tablename)
		{
			$fetchedRows = "";
			$query = "select * from $tablename ";
			//echo $query;exit;
			$result = mysql_query($query );
			$num = mysql_num_rows($result);
			if($num>0)
			{
				while($row = mysql_fetch_assoc($result))
				{					
					foreach($row as $key=>$value)
					{
						$row[$key]=jp_decode(stripslashes($value));
					}
					$fetchedRows[] = $row;
				}
			}
			return $fetchedRows;
		}
		
		public function GetAllRowsByCondition($tablename,$where)
		{
			$query = "select * from $tablename where  ".$where;
			//echo $query;exit;
			$result = mysql_query($query);
			$num = mysql_num_rows($result);
			if($num>0)
			{
				while($row = mysql_fetch_assoc($result))
				{
					foreach($row as $key=>$value)
					{
						$row[$key]=jp_decode(stripslashes($value));
						
					}
					$fetchedRows[] = $row;
				}
			}
			return $fetchedRows;
		}
		
		public function InsertData($tablename, $filedValArray)
		{
			if(is_array($filedValArray))
			{	
				//print_r($filedValArray);exit;
				$insertvalues = "";
				$columns = "";
				foreach($filedValArray as $key=>$filedVal)
				{
					$val = $filedVal;
					//$val = "'".$val."'"					
					//$fieldname = array_search($filedVal,$filedValArray);
					$vals .= "'".jp_encode(addslashes($val))."',";	
					$key = $key.",";			
					$keys .= $key;
				}
				$vals = substr($vals, 0, -1);
				$keys = substr($keys, 0, -1);
				
				$qry = "insert into $tablename($keys) values ($vals)";
				//echo $qry;exit;
				$result = mysql_query($qry);
				if($result)
				{
					return mysql_insert_id();
				}
				else 
				return 0;
			}
			else
			return 0;
		}
		
		public function UpdateData($tablename, $filedValArray, $where)
		{
			if(is_array($filedValArray))
			{	
				$string = "";
				$columns = "";
				foreach($filedValArray as $key=>$filedVal)
				{
					$val = $filedVal;
					$string .= "`".$key."`='".jp_encode(addslashes($val))."',";			
				}
				
				$string = substr($string, 0, -1);
				
				$qry = "update $tablename set $string where ".$where;
				//echo $qry;exit;
				$result = mysql_query($qry);
				if($result)
				{
					return true;
				}
				else
				{
					return false;
				}				
			}
			else
			{
				return false;
			}			
			
		}
		
		public function DeleteRow($tablename, $where)
		{
			$query = "delete from $tablename where $where";
			//echo $query;exit;
			$result = mysql_query($query);
		}
		
		public function pagination($table, $where, $data_per_page,$current_page,$redirect_url)
		{
			$sql = "select count(*) AS total_record from $table where $where";
			//echo $sql;exit;
			$db_result=mysql_query($sql);
			if($db_row=mysql_fetch_array($db_result))
			{
				$total_record=$db_row['total_record'];
			}
			$total_record  = (int)$total_record;
			$total_page=ceil($total_record/$data_per_page);
			//echo $total_page;
			$current_page=(int)$current_page;
			
			if($current_page>$total_page) $current_page=$total_page;
			
			//if($current_page<=0) $current_page=1;
			
			if($current_page+1 >=$total_page)
				$next=$current_page;
			else
				$next=$current_page+1;
			
			if($current_page<=0)
				$prev=-1;
			else
				$prev=$current_page-1;
			if(strpos($redirect_url,'?') !== false)
			{
				$url_sign = '&';
			}
			else
			{
				$url_sign = '?';
			}	
			$pagination = '<div class="pagination">';
			if($total_page > 1)
			{
				if($prev < $current_page && $prev >= 0)
				{
					$pagination.= '|  <a href="'.$redirect_url.$url_sign.'page='.$prev.'">&lt;&lt;前へ</a>';
				}
			
				if(($prev < $current_page && $prev >= 0) || $next > $current_page)
				{
					$pagination.= ' | ';
				}
				
				if($next>$current_page)
				{
					$pagination .= '<a href="'.$redirect_url.$url_sign.'page='.$next.'">次へ&gt;&gt;</a> |';
				}
			}
			$pagination .='</div>';
			return $pagination;
			
		
		}
		
		public function sortItem($table,$id,$move,$redirect= "")
		{
			$query_string = "SELECT * FROM $table WHERE id='".$id."' LIMIT 0,1";
			$db_result = mysql_query($query_string); 
			if($db_row=mysql_fetch_array($db_result))
			{
				$displayorder=$db_row['display_order'];
				//$displaydate=$db_row['displaydate'];
				
				if($move=='up')
					$query_string = "SELECT * FROM $table WHERE display_order >= '".$displayorder."'  ORDER BY display_order ASC LIMIT 0,2";
				else
					$query_string = "SELECT * FROM $table WHERE display_order <= '".$displayorder."'  ORDER BY display_order DESC LIMIT 0,2";
				
				
				$db_result = mysql_query($query_string); 
				
				$sortid=array();
				$sortorder=array();
				$i=0;
		
				while($db_row=mysql_fetch_array($db_result))
				{
					$sortid[$i]		=	$db_row['id'];
					$sortorder[$i]	=	$db_row['display_order'];
					$i++;
				}
				
				if($i>1)
				{
					$query_string = "UPDATE $table SET display_order= '".$sortorder[0]."' WHERE id = '".$sortid[1]."'";
					mysql_query($query_string); 
					
					$query_string = "UPDATE $table SET display_order= '".$sortorder[1]."' WHERE id = '".$sortid[0]."'";
					mysql_query($query_string);
				}
			}
			if($redirect !="")
			{
				redirect($redirect);
			}
		}

		public function sortItem2($table,$id,$move,$redirect= "")
		{
			$query_string = "SELECT * FROM $table WHERE id='".$id."' LIMIT 0,1";
			$db_result = mysql_query($query_string); 
			if($db_row=mysql_fetch_array($db_result))
			{
				$displayorder=$db_row['display_order'];
				//$displaydate=$db_row['displaydate'];
				
				if($move=='up')
					$query_string = "SELECT * FROM $table WHERE display_order <= '".$displayorder."'  ORDER BY display_order DESC LIMIT 0,2";
				else
					$query_string = "SELECT * FROM $table WHERE display_order >= '".$displayorder."'  ORDER BY display_order ASC LIMIT 0,2";
				
				
				$db_result = mysql_query($query_string); 
				
				$sortid=array();
				$sortorder=array();
				$i=0;
		
				while($db_row=mysql_fetch_array($db_result))
				{
					$sortid[$i]		=	$db_row['id'];
					$sortorder[$i]	=	$db_row['display_order'];
					$i++;
				}
				
				if($i>1)
				{
					$query_string = "UPDATE $table SET display_order= '".$sortorder[0]."' WHERE id = '".$sortid[1]."'";
					mysql_query($query_string); 
					
					$query_string = "UPDATE $table SET display_order= '".$sortorder[1]."' WHERE id = '".$sortid[0]."'";
					mysql_query($query_string);
				}
			}
			if($redirect !="")
			{
				redirect($redirect);
			}
		}


public function japanyDateFormate($rawTime, $time_24h=0) {
	
	//午前／午後hh時mm分
	$date = strftime('%Y年%m月%d日',strtotime($rawTime));
	$day = strftime('%A',strtotime($rawTime));
	$weekday_E = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );
	$weekday_J = array( "日", "月", "火", "水", "木", "金", "土" );
	$keys = array_keys($weekday_E, $day);
	echo $date."(".$weekday_J[$keys[0]].")";
	
	if($time_24h)
	{
		$day_TE = array( "PM", "AM" );
		$day_TJ = array( "午後", "午前");
		$timepart= strftime('%p',strtotime($time_24h));
		$keys_T = array_keys($day_TE, $timepart);
		
		$time = strftime('%I時%M分',strtotime($time_24h));
		echo $day_TJ[$keys_T[0]].$time;
	}
	//print_r($keys);g:i a
}

public function japanyDateFormate_for_mail($rawTime) {
	
	
	//午前／午後hh時mm分
	$date = strftime('%Y年%m月%d日',strtotime($rawTime));
	$day = strftime('%A',strtotime($rawTime));
	$weekday_E = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );
	$weekday_J = array( "日", "月", "火", "水", "木", "金", "土" );
	$keys = array_keys($weekday_E, $day);
	return $mail_date = $date."(".$weekday_J[$keys[0]].")";

}


public function japanyDateFormateShort($rawTime, $time_24h=0) {
	
	//午前／午後hh時mm分
	$date = strftime('%m月%d日',strtotime($rawTime));
	echo $date;
	
	if($time_24h)
	{
		$day_TE = array( "PM", "AM" );
		$day_TJ = array( "午後", "午前");
		$timepart= strftime('%p',strtotime($time_24h));
		$keys_T = array_keys($day_TE, $timepart);
		
		$time = strftime('%I時%M分',strtotime($time_24h));
		echo $day_TJ[$keys_T[0]].$time;
	}
	//print_r($keys);g:i a
}

public function japanyDateFormateShortWithWeek($rawTime, $time_24h=0) {
	
	//午前／午後hh時mm分
	$date = strftime('%m月%d日',strtotime($rawTime));
	$day = strftime('%A',strtotime($rawTime));
	$weekday_E = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );
	$weekday_J = array( "日", "月", "火", "水", "木", "金", "土" );
	$keys = array_keys($weekday_E, $day);
	echo $date."(".$weekday_J[$keys[0]].")";
	
	if($time_24h)
	{
		$day_TE = array( "PM", "AM" );
		$day_TJ = array( "午後", "午前");
		$timepart= strftime('%p',strtotime($time_24h));
		$keys_T = array_keys($day_TE, $timepart);
		
		$time = strftime('%I時%M分',strtotime($time_24h));
		echo $day_TJ[$keys_T[0]].$time;
	}
	//print_r($keys);g:i a
}

public function date_dashes_convert($date)
{	
	if($date!="" && $date!="0000-00-00")
	{
		echo $date = strftime('%Y/%m/%d',strtotime($date));
	}
	else{echo "----/--/--";}
}

public function stripslashes2($string) 
{
	$string = str_replace("\\\"", "\"", $string);
	$string = str_replace("\\'", "'", $string);
	$string = str_replace("\\\\", "\\", $string);
	return $string;
}

		public function GetSuccessMsg($msg)
		{
			GLOBAL $message_array;
			$succ_msg = "<div id='msg_rpt' style='text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:green;font-weight:bold;font-size:13px;'>";
			$succ_msg.=$message_array[$msg];
			$succ_msg.="</div>";
			print $succ_msg;
		}
		public function GetErrorMsg($err)
		{
			GLOBAL $error_array;
			$err_msg = "<div id='msg_rpt' style='text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:red;font-weight:bold;font-size:13px;'>";
			$err_msg.=$error_array[$err];
			$err_msg.="</div>";
			print $err_msg;
		}
	public function GetErrorMsgNew($err)
		{
			GLOBAL $error_array;
			
			$err_msg.=$error_array[$err];
			
			return $err_msg;
		}
	public function GetSuccessMsgNew($msg)
	{
		GLOBAL $message_array;
		
		$succ_msg.=$message_array[$msg];
	
		return $succ_msg;
	}


		
	}
?>
