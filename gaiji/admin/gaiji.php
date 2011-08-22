<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");

include_once("inc/header.inc.php");


?>
  <div id="topnavi">
    
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents"> 
      <h2><a href="manage.php">TOP</a> &raquo; ホテル管理</h2>
       <?php
	 $query_string="SELECT count(id) AS total_record FROM gaiji_option;";
	$db_result=mysql_query($query_string);
	if($db_row=mysql_fetch_array($db_result))
	{
		$total_record=$db_row['total_record'];
	}

	$total_record  = (int)$total_record;
	$data_per_page = 2;
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
	
	
	
$i=(int)($current_page-1)*$data_per_page;	?>
	  
      <p><a href="gaiji_add.php">Add</a></p><? if($total_page >1) {?>
      <div class="subtitle" style="text-align:right;">
	  <?php
		if($prev<$current_page&&$prev>0)
			{
				echo '|  <a href="gaiji.php?page='.$prev.'">&lt;&lt;前へ</a>';
			}
		
		if(($prev<$current_page&&$prev>0)||$next>$current_page)
			{
				echo ' | ';
			}
		
		if($next>$current_page)
			{
				echo '<a href="gaiji.php?page='.$next.'">次へ&gt;&gt;</a> |';
			}
	?>
	  
	  </div><? }?>
      <div class="box4">
        <table border="0" align="center" cellpadding="1" cellspacing="1" width="100%">
          <tr align="center">
            <td width="30"><p>SL&#13;</p></td>
			<td width="100"><p>Gaiji name</p></td>
            <td width="100"><p>Jis code</p></td>
			<td width="100"><p>option code</p></td>
			<td width="100"><p>Detail code</p></td>
			<td width="100"><p>CSV No</p></td>
            <td width="100">詳細・編集</td>
			<td>SORT</td>
			<td>Edit</td>
            <td>削除</td>
          </tr>
        </table>
      </div>
	  <?
	$query_string="SELECT * FROM gaiji_option ORDER BY displayorder DESC LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";
	$db_result=mysql_query($query_string);
	if(mysql_num_rows($db_result))
	{
		while($db_row=mysql_fetch_array($db_result))
		{
		
		$class=($i%2==0)?"box5":"box6";
		?>	
		  <div class="<?=$class?>">
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="100%">
			  <tr align="center">
				<td width="30"><p><?=++$i?></p></td>
				<td width="100"><p><?=$db_row['g_name']?></p></td>
				<td width="100"><p><?=$db_row['g_jis_code']?></p></td>
				<td width="100"><p><?=$db_row['option_code']?></p></td>
				<td width="100"><p><?=$db_row['g_detail_code']?></p></td>
				<td width="100"><p><?=$db_row['g_csv_no']?></p></td>
				<td width="100">
				 <span class="txt1"><a href="gaiji_sort.php?page=<?=(int)$_GET['page']?>&amp;move=up&amp;id=<?=$db_row['id']?>">▲</a> 
                             <a href="gaiji_sort.php?page=<?=(int)$_GET['page']?>&amp;move=down&amp;id=<?=$db_row['id']?>"> ▼</a></span>
				</td>
				<td><a href="gaiji_edit.php?id=<?=$db_row['id']?>&page=<?=$_GET['page']?>"> <img src="img/common/btn_edit02.png" alt="詳細・編集" width="57" height="17" /></a></td>
				
				<td><a href="delete.php?pagename=gaiji&t=option&id=<?=$db_row['id']?>&page=<?=$_GET['page']?>" onclick="return confirm('削除しても宜しいですか？');"> <img src="img/common/btn_deleate.png" alt="削除" width="42" height="17" /></a></td>
			  </tr>
			</table>
		  </div>
		  <?php
			
		  }
   }
	  ?>
      
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
