
/* 外字のフォームから外字を削除した際の動作
   input_id 
   div_image delete for image　外字のイメージを入力しているエレメントid
   form_name delete for input　外字のデータを格納しているフォーム名 img[]等はの除く
   input_ele inputを追加する要素
 */
function setDeleteGaiji(gaiji_obj){
  var text = "";
  var gaiji = 0;
  var getGaijiNum = function(text){
    return text.split("＊").length-1;
  }
  var id = "#"+gaiji_obj["input_id"];
  //if (jQuery.browser.msie) {
      $j(id).click(function(event){
          if(!event) event = window.event;
          var target = event.target;
          if(!event.target) target = ele;
          if(!target && event.fromElement){
              target = event.fromElement;
          }else if(!target){
              target = event.srcElement;
          }
          nowGaijiIndex = $j(event.target).getForcusIndex();
          
      });
  //}
  $j(id).keydown(function(event){
      text = $j(this).val();
      gaiji = getGaijiNum(text);
    });
  $j(id).keyup(function(event) {
      var nowtext = $j(this).val();
      var nowgaiji = getGaijiNum(nowtext);
      var nowIndex = 0;
      var mark = "＊";
      
      if(!event) event = window.event;
      var target = event.target;
      if(!event.target) target = ele;
      if(!target && event.fromElement){
          target = event.fromElement;
      }else if(!target){
          target = event.srcElement;
      }
      //if(event.keyCode==13) return;
      //alert(event.keyCode);
      nowGaijiIndex = $j(target).getForcusIndex();
          //alert(nowGaijiIndex);
      if(event.keyCode == 8){
        if(nowgaiji < gaiji){
          var textnum = text_num(nowtext,mark,nowGaijiIndex);
            var nowIndex = 0;
            var children = $j("#"+gaiji_obj["input_ele"]).children();
            for(var i=0;i<children.length;++i){
                nowIndex = i/2|0;
                var now = $j(children[i]).attr("name");
                now = now.replace(/\[\d*\]/,"["+nowIndex+"]");
                $j(children[i]).attr("name",now);
            }
          var inputs = $j('input[name='+gaiji_obj["form_name"]+'img['+textnum+']]');
          $j(inputs).remove();
          var inputs = $j('input[name='+gaiji_obj["form_name"]+'gid['+textnum+']]');
          $j(inputs).remove();
            var inputs = $j('input[name='+gaiji_obj["form_name"]+'gsid['+textnum+']]');
          $j(inputs).remove();
          var images = $j("#"+gaiji_obj["div_image"]+" > img");
          $j(images[textnum]).remove();
        }
      }
   });
};



/*
入力された文字に外字が入っているかどうか判定。
*/
function checkGaiji(str,path,ele){
    var return_str = "";
    $j.ajax({
           "url" :path,
           "success":function(txt){
               if(txt != ""){
                   alert("外字、または、JIS規格による異形文字が含まれています\n●の漢字を外字検索より正しく入力し直してください\n");
                   
                   for(var i=0;i<txt.length;++i){
                       $j(ele).val(String($j(ele).val()).replace(txt[i],"●"));
                       var index = String($j(ele).val()).search("●");
                   }
                   $j(ele).forcusAtChar(index+1);
               }
               return_str = txt;
           },
           "async":false,
           "data":{d:str}
        });
    if(return_str == "") return true;
    return false;
}


function set_gaiji(name,target_ele,input_ele,img_ele,img,gid,gsid,img_link){
  if(!img_link) img_link = "..";
  var mark = "＊";
  var nametext = $j("#"+target_ele).val();
  var textNum = text_num(nametext,mark,nowGaijiIndex);
  var children = $j("#"+input_ele).children();
  var nowIndex = 0;
  for(var i=0;i<children.length;++i){
    nowIndex = i/2|0;
    if(nowIndex>textNum-1){
      nowIndex=nowIndex+1;
    }
    var now = $j(children[i]).attr("name");
    now = now.replace(/\[\d*\]/,"["+nowIndex+"]");
    $j(children[i]).attr("name",now);
  }
  var nowIndex = textNum;
  $j("#"+input_ele).appendIndex("<input type='hidden' name='"+name+"_gaiji_img["+nowIndex+"]' value='"+img+"'>",textNum*2);
  $j("#"+input_ele).appendIndex("<input type='hidden' name='"+name+"_gaiji_gid["+nowIndex+"]' value='"+gid+"'>",textNum*2);
  //$("#"+input_ele).appendIndex("<input type='hidden' name='"+name+"_gaiji_gsid["+nowIndex+"]' value='"+gsid+"'>",textNum*2);
  $j("#"+img_ele).appendIndex("<img src='"+img_link+"/gaiji-image/img_ans/"+img+"' wight='20' height='20'>",textNum);

  $j("#"+target_ele).attr("value", append_text(nametext,mark,nowGaijiIndex));
}

//onblur="set_gaiji_position();"
var nowGaijiIndex = 0;


function set_gaiji_position(ele){
    if (!jQuery.browser.msie) {
        nowGaijiIndex = $j(event.target).getForcusIndex();
    }
}
