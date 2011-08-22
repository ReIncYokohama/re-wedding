<?
  include_once('admin/inc/dbcon.inc.php');
  require_once ('admin/zipcode.inc');
  
  $query  ="SELECT * FROM `zipcode_address` WHERE zipcode = '".trim($_GET['code'])."'";

  $result = mysql_query($query);
  
  $row    =  mysql_fetch_assoc($result);
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
 function submitform()
 {
		var start1 = document.getElementById('start1').value;
		
		var sta = window.opener.document.getElementById('state');
		
		for (var i=0; i < sta.length; i++)
		{
		   if(start1 == sta.options[i].value)
		   {			   
				sta.options[i].selected=true;
			}
		   
		}
		
		window.opener.document.getElementById('city').value = document.getElementById('city1').value;
		
		window.close();
 }
 
</script>
</head>
<body>
<?php if($row['city']!=""){ ?>
<b>検索結果</b>
<table width="400" align="center" border="0" cellspacing="2" cellpadding="1">
  <tr>
    <td>郵便番号</td>
    <td><?=$_GET['code']?></td>
  </tr>
  <tr>
    <td>住所</td>
    <td><input type="text" name="start1" id="start1" value="<?=jp_decode($row['state'])?>" size="20"/><br><input type="text" name="city1" id="city1" value="<?=jp_decode($row['city'])?>" size="50"/> 
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="入力"  onclick="submitform()"/> <br />
    <p><a href="#" onclick="javascript:window.close()">閉じる</a></p>
    </td>
  </tr>
  
</table>
<?php  } else {?>
<center>
<table width="300" bgcolor="#000000" border="0" cellpadding="2" cellspacing="1" height="100">
  
    <tbody><tr>
      <td width="100%" align="center" bgcolor="#cccccc"><font size="2">郵便番号に半角数字以外の文字が含まれています。</font></td>
    </tr>
  
</tbody></table>
<p align="center"><a href="#" onclick="javascript:window.close()">閉じる</a></p></center>
<?php  } ?>

</body>
</html>
