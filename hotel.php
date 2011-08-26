<?php
require_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");

if(isset($_GET['action']) && $_GET['action'] == 'delete' && (int)$_GET['id'] > 0 )
{
	$obj->DeleteRow("super_spssp_hotel"," id =".(int)$_GET['id']);
}
?>
  <div id="topnavi">

    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      <h2>ホテル管理</h2>
      <div class="subtitle">ホテル名検索</div>
	  <form action="hotel.php" name="hotel_search" method="get">
		  <div class="top_searchbox1">
			<table width="420" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><input name="hotel_name" type="text" value="<?=$_GET['hotel_name']?>" class="input_text" id="hotel_name" size="25" />
				※ホテル名を入力して検索してください</td>
		  </tr>
	  </table>
		  </div>
		  <div class="top_searchbox2"><a href="javascript: submitform()"><img src="img/common/btn_search1_admin.jpg" alt="検索" /></a>　<a href="hotel_input.php"><img src="img/common/new_register_admin.jpg" alt="新規登録" /></a> </div>
	  </form>


      <p>&nbsp;</p>
      <div class="subtitle">　ホテル一覧</div>
      <div class="box4">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>ホテルコード&#13;</p></td>
            <td><p>ホテル名&#13;</p></td>
            <td>詳細・編集</td>
            <td>ホテル画面へ</td>
            <td>削除</td>
          </tr>
        </table>
      </div>
	  <?php
	  if($_GET['hotel_name'])
	  $where_query=" hotel_name like '%".$_GET['hotel_name']."%' order by id desc";
	  else
	  $where_query=" 1 = 1 order by id desc";

	  		$hotel_list = $obj->GetAllRowsByCondition(" super_spssp_hotel ",$where_query);

			$i=1;
			if(is_array($hotel_list))
			foreach($hotel_list as $hotel)
			{

				$class=($i%2==0)?"box5":"box6";
		?>
      <div class="<?=$class?>">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p><?=$hotel['hotel_code']?></p></td>
            <td><p><?=$hotel['hotel_name']?>&#13;</p></td>
            <td><a href="hotel_edit.php?id=<?=$hotel['id']?>"> <img src="img/common/btn_edit02.png" alt="詳細・編集" width="57" height="17" /></a></td>

<!-- UCHIDA EDIT 11/08/11 デモ用に登録したホテルＩＤでホテル画面に遷移させる -->
<!--             <td><a href="#"><img src="img/common/hotel_display.png" width="61" height="17" alt="ホテル画面" /></a></td> -->
            <td>
			<a href="../hotel<?php echo (int)$hotel['hotel_code']?>/admin/index.php?key=<?=md5($hotel['email'])?>"><img src="img/common/hotel_display.png" width="61" height="17" alt="ホテル画面" /></a>
			</td>

            <td><a href="hotel.php?action=delete&id=<?=$hotel['id']?>" onclick="return confirm('削除しても宜しいですか？');"> <img src="img/common/btn_deleate.png" alt="削除" width="42" height="17" /></a></td>
          </tr>
        </table>
      </div>
	  <?php
	  		$i++;
	  		}
	  ?>

    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
<script type="text/javascript">
function submitform()
{
    document.forms["hotel_search"].submit();
}
</script>