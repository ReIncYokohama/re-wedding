<?php
session_start();
$user_id = $_GET["user_id"];
$guest_id = $_GET["guest_id"];
$option = $_GET["option"];
$message = $_GET["message"];
require_once("admin/inc/class_information.dbo.php");
$objInfo = new InformationClass();

//redirect("my_guests.php?".$message."=true&page=".$guest_id."&option=".$_GET['option']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
ただいま招待者の画像データを更新しております。<br>
自動的にもとの画面に戻ります。
<?php
$srcArray = array("thumb1/comment1.png","thumb1/comment2.png","thumb1/guest_fullname_only.png");
for($i=0;$i<count($srcArray);++$i){
  echo $objInfo->get_user_name_image_or_src($user_id ,1, "guest/".$guest_id."/".$srcArray[$i],"",1,"",1);
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
      $_SESSION["tmp_url"] = 'window.location.href="my_guests.php?'.$message.'=true&page='.$guest_id.'&option='.$option."\";";
      echo "window.location.reload();";
    }else{
      //echo $_SESSION["tmp_url"];
      $_SESSION["tmp_url"] = null;
    }
    
    ?>
 });

</script>

</body>
</html>


