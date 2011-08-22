<div id="sidebar">
	<div id="stuffname">
    	<img src="img/common/nav_stuffname.gif" alt="TOP" width="148" height="30" />
         <?php
			$staff_name=$obj->GetSingleData("spssp_admin", "name"," id=".(int)$_SESSION['adminid']);
		?>
		<div id="stuffname_txt">
			<div style="font-size:18px;"><?=$staff_name;?></div>
		</div>
	</div>
    <ul class="nav">
        <li><a href="manage.php"><img src="img/common/nav_top.gif" alt="TOP" width="148" height="30" class=on /></a></li>
        <?php
        	if($_SESSION['user_type'] == 111)
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
        <li><a href="rooms.php"><img src="img/common/nav_layout.gif" alt="会場ごとの最大卓レイアウト設定" class=on /></a></li>

        <li><a href="gift.php"><img src="img/common/nav_gift.gif" alt="引出物・料理" width="148" height="30" class=on /></a></li>

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
