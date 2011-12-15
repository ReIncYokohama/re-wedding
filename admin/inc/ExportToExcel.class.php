<?php
/*Author: Raju Mazumder
  email:rajuniit@gmail.com
  Class:A simple class to export mysql query and whole html and php page to excel,doc etc*/



class ExportToExcel
{
	
	function exportWithPage($php_page,$excel_file_name)
	{
		$this->setHeader($excel_file_name);
		require_once "$php_page";
	
	}
	function setHeader($excel_file_name)//this function used to set the header variable
	{
		
		header("Content-type: application/octet-stream");//A MIME attachment with the content type "application/octet-stream" is a binary file.
		//Typically, it will be an application or a document that must be opened in an application, such as a spreadsheet or word processor. 

		header("Content-Disposition: attachment; filename=$excel_file_name");//with this extension of file name you tell what kind of file it is.
    
    header("Cache-Control: public");
    header("Pragma: public");
		//header("Pragma: no-cache");//Prevent Caching
		//header("Expires: 0");//Expires and 0 mean that the browser will not cache the page on your hard drive
	
	}
	function exportWithQuery($qry,$excel_file_name,$conn)//to export with query
	{
		$tmprst=mysql_query($qry,$conn);
		$header="<center><table border=1px><th colspan=5>Personal Details</th>";
		$num_field=mysql_num_fields($tmprst);
		while($row=mysql_fetch_array($tmprst,MYSQL_BOTH))
		{
			$body.="<tr>";
			for($i=0;$i<$num_field;$i++)
			{
				$body.="<td>".$row[$i]."</td>";
			}
			$body.="</tr>";	
		}
		
		$this->setHeader($excel_file_name);
		echo $header.$body."</table";
	}


}
?>
