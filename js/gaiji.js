
/* 外字のフォームから外字を削除した際の動作
   input_id 
   div_image delete for image　外字のイメージを入力しているエレメントid
   form_name delete for input　外字のデータを格納しているフォーム名 img[]等はの除く
 */
function setDeleteGaiji(gaiji_obj){
  var text = "";
  var gaiji = 0;
  var getGaijiNum = function(text){
    return text.split("＊").length-1;
  }
  var id = "#"+gaiji_obj["input_id"];
  $j(id).keydown(function(event){
      text = $j(this).val();
      gaiji = getGaijiNum(text);
    });
  $j(id).keyup(function(event) {
      var nowtext = $j(this).val();
      var nowgaiji = getGaijiNum(nowtext);
      if(event.keyCode == 8){
        if(nowgaiji < gaiji){
          var inputs = $j('input[name='+gaiji_obj["form_name"]+'img[]]');
          $j(inputs[inputs.length-1]).remove();
          var inputs = $j('input[name='+gaiji_obj["form_name"]+'gid[]]');
          $j(inputs[inputs.length-1]).remove();
          var inputs = $j('input[name='+gaiji_obj["form_name"]+'gsid[]]');
          $j(inputs[inputs.length-1]).remove();
          var images = $j("#"+gaiji_obj["div_image"]+" > img");
          $j(images[images.length-1]).remove();
        }
      }
   });
};
