<?php
include_once("../fuel/load_classes.php");
?>
<style>
#text-indent {
	text-indent: 125px; /* SEKIDUKA ADD 11/08/12 */
}
#foot_left {
	float: left; /* SEKIDUKA ADD 11/08/12 */
	width: 140px;
	text-align: left;
}
#foot_center {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
#foot_right {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
.clr {	clear: both;
}
</style>

<div id="sidebar">
	<div id="stuffname">
    	<img src="img/common/nav_stuffname.gif" alt="TOP" width="148" height="30" />
		<div id="stuffname_txt">
  <div style="font-size:18px;"><?php echo Core_Session::get_staff_name();?></div>
		</div>
	</div>
    <ul class="nav">
        <li><a href="manage.php"><img src="img/common/nav_top.gif" alt="TOP" width="148" height="30" class=on /></a></li>
        <?php
  if(Core_Session::is_super())
			{
		?>
        <li><img src="img/common/management.gif" alt="ホテル管理" width="148" height="30" /></li>
        <?php
			}
		?>
        <li><a href="hotel_info.php"><img src="img/common/rules.gif" alt="TOP" width="148" height="30"/></a></li>
        <li><a href="staffs.php"><img src="img/common/nav_stuff.gif" alt="スタッフ" width="148" height="30" class=on /></a></li>
        <li><a href="invitation.php"><img src="img/common/nav_invetation.gif" alt="招待状" width="148" height="30" class=on /></a></li>
        <li><img src="img/common/nav_sekiji.gif" alt="席次表・席札" width="148" height="30" /></li>
            <li><a href="default.php"><img src="img/common/nav_table_name.gif" alt="基本設定" class=on /></a></li>
            <li><a href="religions.php"><img src="img/common/wedding_cate.gif" alt="挙式種類" width="148" height="20" class=on /></a></li>
        <?php
        	if($_SESSION['user_type'] == 111)
			{
		?>
            <li><a href="respects.php"><img src="img/common/title.gif" alt="敬称" width="148" height="20" class=on /></a></li>
            <li><a href="guest_types.php"><img src="img/common/groups_set.gif" alt="区分の設定" width="148" height="20" class=on /></a></li>
        <?php
			}
		?>
        <li><a href="rooms.php"><img src="img/common/nav_layout.gif" alt="会場ごとの最大卓レイアウト設定" class="on" /></a></li>

        <li><a href="gift.php"><img src="img/common/nav_gift.gif" alt="引出物・料理" width="148" height="30" class="on" /></a></li>

        <li><a href="printing_company.php"><img src="img/common/printing_company.gif" alt="printing_company" width="148" height="30" class=on /></a></li>

        <li><a href="useridlimit.php"><img src="img/common/nav_timelimit.gif" alt="limit_date" class=on /></a></li>

         <?php
        	if($_SESSION['user_type'] == 111 || $_SESSION['user_type'] == 333)
			{
		?>
		<li><a href="users.php"><img src="img/common/admin_customer_List.gif" alt="お客様一覧" width="148" height="43" class=on /></a></li>

       <?php
			}
		?>
	    </ul>
        <br />
<div id="foot_left">
<table border="0" cellpadding="2" cellspacing="0" title="SSLサーバ証明書導入の証 グローバルサインのサイトシール">
<tr>
<td align="center" valign="top"> <span id="ss_img_wrapper_115-57_image_ja">
<a href="http://jp.globalsign.com/" target="_blank"> <img alt="SSL グローバルサインのサイトシール" border="0" id="ss_jpn2_gif" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_115-57_ja.gif">
</a>
</span><br>
<script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gs_image_115-57_ja.js" defer="defer"></script> <a href="https://www.sslcerts.jp/" target="_blank" style="color:#000000; text-decoration:none; font:bold 12px 'ＭＳ ゴシック',sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">SSLとは?</a>
</td>
</tr>
</table>
</div>
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
</div>
