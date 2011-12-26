<?php
require_once("inc/class_information.dbo.php");
session_start();
$user_id = $_GET["user_id"];
$objInfo = new InformationClass();

//redirect("my_guests.php?".$message."=true&page=".$guest_id."&option=".$_GET['option']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
ただいま新郎新婦名の画像データを更新しております。<br>
自動的にもとの画面に戻ります。
<?php
  $srcArray = array("thumb1/man_lastname.png","thumb1/woman_lastname.png","thumb1/man_fullname.png","thumb1/woman_fullname.png","guest_page.png");
for($i=0;$i<count($srcArray);++$i){
  echo $objInfo->get_user_name_image_or_src($user_id ,1, $srcArray[$i],"",1,"",10);
?>
<?php
}
?>
<script type="text/javascript">
  function sleep( T ){ 
  var d1 = new Date().getTime(); 
  var d2 = new Date().getTime(); 
  while( d2 < d1+1000*T ){    //T秒待つ 
    d2=new Date().getTime(); 
  } 
  return; 
} 
$(window).load(function() {
    <?php

    if(!$_SESSION["tmp_url"]){
      echo "sleep(2);";
      $_SESSION["tmp_url"] = 'window.location.href="user_info_allentry.php?user_id='.$user_id.'";';
      echo "window.location.reload();";
    }else{
      //echo "sleep(10);";
      echo $_SESSION["tmp_url"];
      $_SESSION["tmp_url"] = null;
    }
    
    ?>
 });

</script>

</body>
</html>