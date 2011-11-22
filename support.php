<?php
include_once("inc/header.inc.php");
?>
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
  <div id="topnavi">
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      <h2>サポートページ編集</h2>
      <div class="subtitle">サポートページ編集</div>
      <div id="message_BOX" style="overflow:auto;">■ホテル用<br />
        ・<a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')">操作方法 - ホテル用</a><br />
      <br />
      ■ユーザー用<br />
      ・<a href="javascript:;" onclick="MM_openBrWindow('../support/operation_u.html','','scrollbars=yes,width=620,height=600')">操作方法 - ユーザー用</a><br />
      <br />
      ■共通<br />
      ・<a href="javascript:;" onclick="MM_openBrWindow('../support/security.html','','scrollbars=yes,width=620,height=600')">セキュリティ</a><br />
      ・<a href="javascript:;" onclick="MM_openBrWindow('../support/privacy_policy.html','','scrollbars=yes,width=620,height=600')">個人情報保護方針</a><br />
      ・<a href="javascript:;" onclick="MM_openBrWindow('../support/qa.html','','scrollbars=yes,width=620,height=600')">よくある質問 Q&amp;A</a><br />
      ・<a href="javascript:;" onclick="MM_openBrWindow('../support/contact.html','','scrollbars=yes,width=620,height=600')">お問い合わせ</a></div>
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
