<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	require_once('../tcpdf/config/lang/eng.php');
	require_once('../tcpdf/tcpdf.php');
	
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// full background image
		// store current auto-page-break status
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = K_PATH_IMAGES.'contents_bg.jpg';
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
	}
}	
	
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);// create new PDF document


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 008');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('arialunicid0', '', 12);

// add a page
$pdf->AddPage();
/////////////////end of for pdf///////////////////
	
	
	
	
	//include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
		$obj = new DBO();
	
	
	
	$get = $obj->protectXSS($_GET);

	$plan_id = $get['plan_id'];
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id =".$plan_id);
	
	$room_info=$obj->GetSingleRow("spssp_room", " id =".$plan_row['room_id']);
	
	$room_rows = $plan_row['row_number'];
	$room_tables = $plan_row['column_number'];
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");
	for($i_rows=0;$i_rows<$room_tables;$i_rows++)
	{
		for($rows_num=0;$rows_num<count($table_rows);)
		{
			
			$key=$i_rows+$rows_num;	
			$table_new_rows[$i_rows][]=$table_rows[$key];
			$rows_num=$rows_num+$room_tables;
		
		}
	}
	

		
	
	$details =	$obj->getRowsByQuery("select * from spssp_default_plan_details where plan_id=".$plan_id );
	
	$arr = array();
	foreach($details as $dt)
	{
		$seatid = $dt['seat_id'];
		$guestid = $dt['guest_id'];
		$arr[$seatid]= $guestid;
	}


$td_width=(int)(72/$room_rows)*4;
$font_size_guest=(int)(25/$room_rows)*4;

$font_size_table_text=(int)(15/$room_rows)*4;


/*$html='<div style="margin:0;padding:0;">
	<div style="margin:0 auto;padding:0;">
  		<div style="padding:0;float:left;width:auto;">
    		<h1 style="margin:0;padding:29px 24px;float:left;color:#e4e5e5;font:bold 36px/1.2em Arial, Helvetica, sans-serif;letter-spacing:-3px;text-transform:uppercase;"><a href="#">SPSSP <span style="color:#00c6ff;">Desk</span></a> <small style="font:normal 12px/1.2em Arial, Helvetica, sans-serif;letter-spacing:normal;padding-left:32px;">Your Wedding Partner			</small></h1>
  		</div>
	</div>
</div>';*/

$html='<table  width="100%"><tr><td align="center" width="23%" >'.$room_info['hotel_free_text'].'</td><td align="center" width="54%" valign="middle" style="text-align:center;">Male Female Name</td><td></td></tr></table><br/><br/>';

$html.='<table  cellspacing="2" width="100%" style="margin-top:20px;">';		
			


$i=1;

for($table_nums=0;$table_nums<$room_tables;$table_nums++)
{
	$count_table_row_data=1;
	for($rows_nums=0;$rows_nums<$room_rows;$rows_nums++)
	{
			$table_id=$table_new_rows[$table_nums][$rows_nums]['id'];
			$table_name=$table_new_rows[$table_nums][$rows_nums]['name'];
			
			$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc limit 0,$room_seats");
			$count_table_column_data=1;
			$seats_count=1;
			foreach($seats as $seat)
			{
				$div_id=$seat['id'];		
				$key = $seat['id'];

				if(isset($arr) && $arr[$key] != '')
					{
					
						$item = $arr[$key];
						$item_info =  $obj->GetSingleRow("spssp_default_guest", " id=".$item);
						$name=$item_info['name'];
      					
							if($name)
							{
							
									if($count_table_row_data==1)
									{
										$html.="<tr width='100%'><td width='100%' style='text-align:center;'><table  align='center'><tr>";
										
										$count_table_row_data++;
									
									}
									if($count_table_column_data==1)
									{
										$html.='<td  align="center"><table cellspacing="10" align="center"><tr><td colspan="2"><b>'.$table_name.'</b></td></tr>';
										$count_table_column_data++;
									}
									
									//// calculation of seats////////
												if($seats_count==1)
												{
													
													$html.='<tr>';	
												
												}
												$html.='<td style="';
											
												if($seats_count==1)
												{
												$html.=" text-align:right;";
												}
												else
												{
												$html.=" text-align:left;";
												}
												$html.=' "  width="50%" valign="middle">';
												$html.='<b style="font-size:'.$font_size_guest.'px;">'.$name.'</b>';
												$html .='</td>';
												if($seats_count==2)
												{
													$seats_count=0;
													$html.='</tr>';	
												
												}
									
												$seats_count++;	
						
							}
																
	
					}
					
			
			
			}///end of foreach
			if($seats_count==2)
			{
				$html.='<td>&nbsp;</td></tr>';	
			}
			
			
			if($count_table_column_data>1)
			{
				$html.='</table></td>';	
			}	
	
	}///end of first for
	
	if($count_table_row_data>1)
	{
		$html.='</tr></table></td></tr>';	
	}

}///end of second for

$html.="</table>";

$html.='<table><tr><td align="right" width="100%"  style="text-align:right;"><span>'.$room_info['hotel_room_date'].'&nbsp;&nbsp;&nbsp;'.$room_info['hotel_room_title'].'&nbsp;&nbsp;&nbsp;</span></td></tr></table>';





 
 $samplefile="sam_".$plan_id."_".rand()."_".time().".txt";
 
 $handle = fopen("../cache/".$samplefile, "x");
 
 if(fwrite($handle, $html)==true)
 {
 	fclose($handle);
	
 	$utf8text = file_get_contents("../cache/".$samplefile, false);
	
 }

@unlink("../cache/".$samplefile);

 $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $utf8text, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
 $pdf->Output('example_001.pdf', 'I');
?>  
