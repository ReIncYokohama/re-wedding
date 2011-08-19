<?php
	require_once("../admin/inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");
	
	$user_id = (int)$_SESSION['userid'];
	$obj = new DBO();
	$total_table=$_GET['total_table'];
	
	
	if((int)$total_table>0)
	{
		for($loop=1;$loop<=$total_table;$loop++)
		{
			$arr['name'] = $_GET['name_'.$loop];
			
			$id=$_GET['id_'.$loop];
			$r=$obj->UpdateData("spssp_table_layout", $arr, " user_id=".$user_id." and id = ".$id);
		}
	}


				$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
				$num_tables = $obj->getSingleData("spssp_plan", "column_number"," user_id= $user_id");
				$rw_width = (int)($num_tables* 51);

				$z=count($tblrows);
				$i=0;
				$ct=0; // UCHIDA EDIT 11/07/28
				foreach($tblrows as $tblrow)
				{
					$i++;

						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");
						$num_hidden_table = $obj->GetNumRows("spssp_table_layout","user_id = $user_id and display = 0 and row_order=".$tblrow['row_order']);
						if($ralign == 'L')
						{
							$pos = 'float:left;';
						}
						else if($ralign=='R')
						{
							$pos = 'float:right;';
						}
						else
						{
							$wd = $rw_width - ($num_hidden_table*51);
							$pos = 'margin:0 auto; width:'.$wd.'px';
						}

				?>
    			<div  style="float:left;width:100%; border:1px solid black;<?php if($i!=$z) {?> border-bottom:none; <?php } ?>" id="row_<?=$tblrow['row_order']?>">
            	<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />

            		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$pos;?>">
				<?php
                $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");

                    foreach($table_rows as $table_row)
                    {
                    	$new_name_row = $obj->GetSingleRow("spssp_user_table", " user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);


                            $tblname='';
							//print_r($new_name_row);//exit;
                            if($table_row['name']!='')
                            {
$tblname = $table_row['name'];
					//			echo'<pre>';
				//print_r($tblname_row);
                            }
                            elseif(is_array($new_name_row) && $new_name_row['id'] !='')
                            {

							    $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);

								$tblname = $tblname_row['name'];
                            }



                            if($table_row['visibility']==1 && $table_row['display']==1)
                            {

								$ct++; // UCHIDA EDIT 11/07/28
                            	$disp = 'display:block;';

                            }
                            else if($table_row['visibility']==0 && $table_row['display']==1)
                            {
                              	$ct++;
							    $disp = 'visibility:hidden;';
                            }
                            else if($table_row['display']==0 && $table_row['visibility']==0)
                            {
                                $disp = 'display:none;';
                            }
                    ?>
                    <div class="tables" style="<?=$disp?>">
                        <p align="left" style="text-align:center;" id="table_<?=$table_row['id']?>">
                        	<font style='font-size:60%' color="#ff0000"><?echo $ct?></font> <!-- UCHIDA EDIT 11/07/28 -->
                            <b> <?=mb_substr ($tblname, 0,1,'UTF-8');?></b>
                        </p>
                    </div>
				<?php
                    }
                ?>
        			</div>
             	</div>
				<?php
                }
                ?>