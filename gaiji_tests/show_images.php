<?php
//last_name,first_name.comment1,comment2,respect,memo1,memo2,memo3
$fp = fopen("list_image.csv","r");
$view_arr = array("comment_plate","name_plate","full_plate");
while($data = fgetcsv($fp)){
    print_r($data);
  foreach($view_arr as $view){
    $image =  "<image src=\"make_image.php?last_name=".$data[0]."&first_name=".
      $data[1]."&comment1=".$data[2]."&comment2=".$data[3]."&respect=".$data[4]."&gift_name=".
      $data[5]."&menu_name=".$data[6]."&memo=".$data[7]."&view=".$view."\"></image>";
    print $image;
  }
}
