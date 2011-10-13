var _doc = document;

var _Data = (function(){
  this.data = {};
  this.old_data = {};
  //array of object {state(insert,move,delete),guest_id,seat_id,plan_id}
  this.send_data = [];
  this.highlight_guest_id = null;
  this.mode = "add";
  //今表示しているゲストのセルの位置(table_id,seat_id,pageX,pageY)
  this.cells = [];
  this.now_drag_target;
  this.guest_sort_key = "id";
  this.guest_sort_desc = false;
});
_Data.prototype = {
  test:function(){
    alert("test");
  },
  load:function(data){
    this.data = data;
    this.old_data = $.extend({}, this.data);
    var dataClass = this;
    $(document.body).keypress(function (e) {
      switch(e.which){
        case 115://s
          dataClass.next_mode(true);
          break;
        case 102://f
          dataClass.next_mode();
          break;
        case 101://e
          if(dataClass.mode=="add")
            dataClass.next_guest(true);
          break;
        case 100://d
          if(dataClass.mode=="add")
            dataClass.next_guest(false);
          break;
      }
    });
  },
  set_position:function(){
    var dataClass = this;
    $.each($(".seat"), function(i, val) {
      var table_id = $(this).attr("table_id");
      var seat_id = $(this).attr("seat_id");
      var pageX = $(this).offset().left;
      var pageY = $(this).offset().top;
      dataClass.cells.push({"table_id":table_id,"seat_id":seat_id,"pageX":pageX,"pageY":pageY});
    });
  },
  //type,sex
  sort_guests:function(){
    this.data.guests = SortObjectArray(this.data.guests,{type:1,desc:this.guest_sort_desc,key:this.guest_sort_key});
  },
  select_guest:function(guest_id){
    this.highlight_guest_id = guest_id;
    this.guest_view();
  },
  next_guest:function(desc){
    var guest_index = this.get_guest_index(this.highlight_guest_id);
    guest_index = desc?guest_index-1:guest_index+1;
    this.select_guest(this.first_higtlight(guest_index,desc));
  },
  adjust_guest_name: function(ele,f8,c){
    var f8 = f8?f8:7;
    var c = c?c:9;
    var name = String($(ele).text());
    if(name.length>c){
      $(ele).css("font-size","8px").text(name.substring(0,c));
    }else if(name.length>f8){
      $(ele).css("font-size","8px");
    }
  },
  //copy_ele contains (class number,sex,type,name,table_name)
  guest_view:function(){
    this.sort_guests();
    var insert_ele = "guest_view_tbody",copy_ele = "guest_view_copy";
    var guests = this.data.guests;
    var insert_ele = $("#"+insert_ele);
    insert_ele.empty();
    var copy_ele = $("#"+copy_ele);
    var dataClass = this;
    var guest_name_click_handler = function(){
      if(dataClass.mode != "add"){
        dataClass.change_mode($("#add_mode")[0]);
      }
      dataClass.select_guest($(this).attr("guest_id"));
    };
    var guest_view_num = 0;
    var forcus_num;
    
    for(var i=0;i<guests.length;++i){
      if(guests[i]["unset"]) continue;
      var trObject = copy_ele.clone().removeAttr("id").attr("guest_id",guests[i]["id"]);
      trObject.attr("id","guest"+guests[i]["id"]);
      var name = guests[i].last_name+" "+guests[i].first_name;
      this.change_name(
          {sex:(guests[i]["sex"]=="Male"?"新郎":"新婦"),name:name,type:guests[i]["guest_type_value"]}
          ,trObject);
      this.adjust_guest_name($(trObject).children(".name"));
      insert_ele.append(trObject);
      trObject.click(guest_name_click_handler);
      if(this.mode == "add" && this.is_highlight(guests[i]["id"])){
        trObject.addClass("highlight");
        forcus_num = guest_view_num;
      }else if(i%2==0){
        trObject.addClass("odd");
      }else{
        trObject.addClass("even");
      }
      guest_view_num += 1;
      trObject.show();
    }
    //スクロールの大きさを制御
    $("#guest_view_tbody").scrollTop((forcus_num-5)*25);
  },
  table_view_all :function(){
    var rows = this.data.rows;
    for(var i=0;i<rows.length;++i){
      var columns = rows[i]["columns"];
      for(var j=0;j<columns.length;++j){
        var table_id = columns[j]["id"];
        var seats = columns[j]["seats"];
        for(var k=0;k<seats.length;++k){
          var elementName = "#table"+table_id+" .seat"+k;
          if(this.mode == "replace"){
            $(elementName).addClass("nopointer");
          }else if(this.mode == "delete"){
            $(elementName).addClass("nopointer");
            $(elementName).removeClass("drag");
          }else{
            $(elementName).removeClass("nopointer drag");
          }
          if(!seats[k]["guest_id"]) continue;
          var jquery_obj = $("#table"+table_id+" .seat"+k);
          jquery_obj
            .html(seats[k]["guest_detail"]["last_name"]+" "+seats[k]["guest_detail"]["first_name"])
            .attr("guest_id",seats[k]["guest_id"])
            .attr("title","<image src='"+seats[k]["guest_detail"]["name_plate"]+"'/>");
            this.adjust_guest_name(jquery_obj,5,7);
          if(this.mode == "replace"){
            jquery_obj.removeClass("nopointer");
            jquery_obj.addClass("drag");
          }else if(this.mode == "delete"){
            jquery_obj.removeClass("nopointer");
          }
        }
      }
    }
    $(".tooltip").tipTip();
  },
  change_name:function(obj,jquery_obj){
    $.each(obj, function(i, val) {
      jquery_obj.children("."+i).text(val);
    });
  },
  is_highlight:function(guest_id){
    if(this.highlight_guest_id && this.highlight_guest_id == guest_id){
      return true;
    }else if(!this.highlight_guest_id && this.first_higtlight() == guest_id){
      return true;
    }
    return false;
  },
  first_higtlight:function(num,desc){
    if(!num) num = 0;
    var guests = this.data.guests;
    if(desc){
      for(var i=num;i>=0;--i){
        if(!guests[i].unset){
          this.highlight_guest_id = guests[i]["id"];
          return guests[i]["id"];
        }
      }
      for(var i=guests.length-1;i>num;--i){
        if(!guests[i].unset){
          this.highlight_guest_id = guests[i]["id"];
          return guests[i]["id"];
        }
      }
    }
    for(var i=num;i<guests.length;++i){
      if(!guests[i].unset){
        this.highlight_guest_id = guests[i]["id"];
        return guests[i]["id"];
      }
    }
    for(var i=0;i<num;++i){
      if(!guests[i].unset){
        this.highlight_guest_id = guests[i]["id"];
        return guests[i]["id"];
      }
    }
    return false;
  },
  change_mode:function(modeEle){
    $(".mode").removeClass("selected");
    $(modeEle).addClass("selected");
    this.mode = $(modeEle).attr("mode");
    if(this.mode == "delete"){
      $("#guest_view_tbody .highlight").removeClass("highlight");
    }
    this.guest_view();
    this.table_view_all();
  },
  next_mode:function(desc){
    if(desc){
      desc = -1;
    }else{
      desc = 1;
    }
    var modes = $(".mode");
    for(var i=0;i<modes.length;++i){
      if($(modes[i]).attr("mode") == this.mode){
        now_index = i;
        break;
      }
    }
    now_index+=1*desc;
    if(modes.length == now_index) now_index = 0;
    if(now_index == -1) now_index = modes.length-1;
    this.change_mode($(modes[now_index]));
  },
  set_seat_event:function(){
    var dataClass = this;
    
    var save_click_handler = function(){
      if(confirm("保存しますか？")){
        $.post("save_make_plan.php",{data:JSON.stringify(dataClass.send_data)},function(text){
          alert("保存しました。");
          dataClass.send_data = [];
          dataClass.old_data = $.extend({}, dataClass.data);
        });
      }
    };
    $("#save_button").click(save_click_handler);
    
    var change_guest_sort_handler = function(){
      var sort_mode = $(this).attr("sort");
      if(sort_mode == dataClass.guest_sort_key){
        dataClass.guest_sort_desc = !dataClass.guest_sort_desc;
      }else{
        dataClass.guest_sort_desc = true;
      }
      dataClass.guest_sort_key = sort_mode;
      dataClass.guest_view();
    };
    $(".guest_sort").click(change_guest_sort_handler);
    
    var cancel_click_handler = function(){
      if(confirm("元に戻しますか？")){
        location.reload();
      }
    };
    $("#cancel_button").click(cancel_click_handler);

    var mode_click_handler = function(){
      dataClass.change_mode(this);
    };
    $(".mode").click(mode_click_handler);

    var click_handler = function(){
      if(dataClass.mode == "replace") return;
      var seat_id = $(this).attr("seat_id");
      var table_id = $(this).attr("table_id");
      var target_guest_id = $(this).attr("guest_id");
      if(dataClass.mode == "delete"){
        dataClass.guest_delete(table_id,seat_id,target_guest_id);
      }else if(target_guest_id){
        var guest_id = dataClass.highlight_guest_id;
        dataClass.guest_replace(table_id,seat_id,target_guest_id,guest_id);
      }else{
        var guest_id = dataClass.highlight_guest_id;
        dataClass.guest_add(table_id,seat_id,guest_id);
      }
      dataClass.table_view_all();
      dataClass.guest_view();
    };
    $(".seat").click(click_handler);

    var mouse_down_handler = function(){
      if(dataClass.mode != "replace") return;
      var seat_id = $(this).attr("seat_id");
      var table_id = $(this).attr("table_id");
      var guest_id = $(this).attr("guest_id");
      if(!guest_id) return;
      var guest_data = dataClass.get_guest_data(guest_id);
      $("#move_card").show().css({"top":event.pageY-15+"px","left":event.pageX-30}).text(guest_data["last_name"]+" "+guest_data["first_name"]);
      dataClass.adjust_guest_name($("#move_card"),5,7);
      $(this).text("");
      var mouse_move_handler = function(){
        $("#move_card").css({"top":event.pageY-15+"px","left":event.pageX-30});
        var target = dataClass.get_seat_position(event.pageX,event.pageY);
        if(target && target["seat_id"] != seat_id){
          if(dataClass.now_drag_target != target["seat_id"]){
            if(dataClass.now_drag_target) $("#"+dataClass.now_drag_target).removeClass("highlight");
            $("#"+target["seat_id"]).addClass("highlight");
            dataClass.now_drag_target = target["seat_id"];
          }
        }else{
          if(dataClass.now_drag_target) $("#"+dataClass.now_drag_target).removeClass("highlight");
          dataClass.now_drag_target = null;
        }
      };
      var mouse_up_handler = function(){
        if(dataClass.now_drag_target){
          var target_guest_id = $("#"+dataClass.now_drag_target).attr("guest_id");
          var target_table_id = $("#"+dataClass.now_drag_target).attr("table_id");
          var target_seat_id = dataClass.now_drag_target;
          //入れ替え
          if(target_guest_id){
            dataClass.guest_exchange(table_id,seat_id,guest_id,target_table_id,target_seat_id,target_guest_id);
          }else{//新規の移動
            dataClass.guest_move(table_id,seat_id,target_table_id,target_seat_id,guest_id);
          }
          $("#"+dataClass.now_drag_target).removeClass("highlight");
        }
        $("#move_card").hide();
        dataClass.now_drag_target = null;
        $(_doc.body).unbind("mousemove", mouse_move_handler);
        $(_doc.body).unbind("mouseup", mouse_up_handler);
        dataClass.table_view_all();
        dataClass.guest_view();
      };
      $(_doc.body).bind("change", function(){ alert("test");});
      $(_doc.body).bind("mousemove", mouse_move_handler);
      $(_doc.body).bind("mouseup", mouse_up_handler);
    };
    $(".seat").mousedown(mouse_down_handler);
  },
  //ゲストを移動
  guest_move:function(from_table_id,from_seat_id,to_table_id,to_seat_id,guest_id){
    this.send_data.push({state:"move","guest_id":guest_id,"seat_id":to_seat_id,"plan_id":this.data.plan_id});
    this.delete_guest_seat(from_table_id,from_seat_id,guest_id);
    this.add_guest_to_seat(to_table_id,to_seat_id,guest_id);
  },
  //ゲストを削除
  guest_delete:function(table_id,seat_id,guest_id){
    this.send_data.push({state:"delete","guest_id":guest_id,"seat_id":seat_id,"plan_id":this.data.plan_id});
    this.guest_display_unset(guest_id,false);
    this.delete_guest_seat(table_id,seat_id,guest_id);
  },
  //ゲストを新規追加
  guest_add:function(table_id,seat_id,guest_id){
    this.send_data.push({state:"insert",guest_id:guest_id,seat_id:seat_id,plan_id:this.data.plan_id});
    this.add_guest_to_seat(table_id,seat_id,guest_id);
    var guest_index = this.guest_display_unset(guest_id,true);
    this.highlight_guest_id = this.first_higtlight(guest_index);
  },
  //ゲストを交換(席同士)
  guest_exchange:function(from_table_id,from_seat_id,from_guest_id,to_table_id,to_seat_id,to_guest_id){
    this.send_data.push({state:"move","guest_id":from_guest_id,"seat_id":to_seat_id,"plan_id":this.data.plan_id});
    this.send_data.push({state:"move","guest_id":to_guest_id,"seat_id":from_seat_id,"plan_id":this.data.plan_id});
    this.add_guest_to_seat(to_table_id,to_seat_id,from_guest_id);
    this.add_guest_to_seat(from_table_id,from_seat_id,to_guest_id);
  },
  //ゲストを交換(リスト)
  guest_replace:function(table_id,seat_id,from_guest_id,to_guest_id){
    this.send_data.push({state:"delete","guest_id":from_guest_id,"seat_id":seat_id,"plan_id":this.data.plan_id});
    this.send_data.push({state:"insert",guest_id:to_guest_id,seat_id:seat_id,plan_id:this.data.plan_id});
    var guest_index = this.guest_display_unset(to_guest_id,true);
    this.guest_display_unset(from_guest_id,false);
    this.add_guest_to_seat(table_id,seat_id,to_guest_id);
    this.highlight_guest_id = this.first_higtlight(guest_index);
  },
  add_guest_to_seat: function(table_id,seat_id,guest_id){
    this.set_seat_into_guest(table_id,seat_id,guest_id);
  },
  delete_guest_seat:function(table_id,seat_id,guest_id){
    this.delete_guest_data(table_id,seat_id,guest_id);
  },
  set_seat_into_guest: function(table_id,seat_id,guest_id){
    var rows = this.data.rows;
    var set_row = false;
    for(var i=0;i<rows.length;++i){
      var columns = rows[i]["columns"];
      for(var j=0;j<columns.length;++j){
        if(table_id != columns[j]["id"]) continue;
        var seats = columns[j]["seats"];
        for(var k=0;k<seats.length;++k){
          if(seats[k]["id"] != seat_id) continue;
          var guest_data = this.get_guest_data(guest_id);
          var guest_data_clone = $.extend({}, guest_data);
          seats[k]["guest_detail"] = guest_data;
          seats[k]["guest_id"] = guest_id;
          break;
        }
        if(set_row) break;
      }
      if(set_row) break;
    }
  },
  delete_guest_data:function(table_id,seat_id){
    var rows = this.data.rows;
    var set_row = false;
    for(var i=0;i<rows.length;++i){
      var columns = rows[i]["columns"];
      for(var j=0;j<columns.length;++j){
        if(table_id != columns[j]["id"]) continue;
        var seats = columns[j]["seats"];
        for(var k=0;k<seats.length;++k){
          if(seats[k]["id"] != seat_id) continue;
          seats[k]["guest_detail"] = null;
          seats[k]["guest_id"] = null;
          $("#table"+table_id+" .seat"+k).text("").removeAttr("guest_id").removeClass("drag").attr("title"," ");
          break;
        }
        if(set_row) break;
      }
      if(set_row) break;
    }
  },
  guest_display_unset: function(guest_id,unset){
    var guests = this.data.guests;
    for(var i=0;i<guests.length;++i){
      if(guests[i].id == guest_id){
        this.data.guests[i]["unset"] = unset;
        break;
      }
    }
    return i;
  },
  get_guest_data: function(guest_id){
    var index = this.get_guest_index(guest_id);
    if(index !== 0 && !index){
      return false;
    }
    return this.data.guests[index];
  },
  get_guest_index:function(guest_id){
    var guests = this.data.guests;
    for(var i=0;i<guests.length;++i){
      if(guests[i].id == guest_id){
        return i;
      }
    }
    return false;
  },
  get_seat_position: function(pageX,pageY){
    var dataArray = this.cells;
    for(var i=0;i<dataArray.length;++i){
      if(pageX >= dataArray[i]["pageX"] && pageX <= dataArray[i]["pageX"]+70 &&
        pageY >= dataArray[i]["pageY"] && pageY <= dataArray[i]["pageY"]+30){
          return dataArray[i];
      }
    }
    return false;
  }
};