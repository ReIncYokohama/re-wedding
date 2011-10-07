var _Data = (function(){
  this.data = {};
  this.old_data = {};
  //array of object {state(i,u,d),guest_id,seat_id,plan_id}
  this.send_data = [];
  this.highlight_guest_id = null;
  this.deleteMode = false;
});
_Data.prototype = {
  test:function(){
    alert("test");
  },
  load:function(data){
    this.data = data;
  },
  //copy_ele contains (class number,sex,type,name,table_name)
  guest_view:function(copy_ele,insert_ele){
    var insert_ele = "guest_view_tbody",copy_ele = "guest_view_copy";
    var guests = this.data.guests;
    var insert_ele = $("#"+insert_ele);
    insert_ele.empty();
    var copy_ele = $("#"+copy_ele);
    var dataClass = this;
    var guest_name_click_handler = function(){
      if(this.deleteMode) return;
      dataClass.highlight_guest_id = $(this).attr("guest_id");
      dataClass.guest_view();
    };
    var guest_view_num = 0;
    var forcus_num;
    for(var i=0;i<guests.length;++i){
      if(guests[i]["unset"]) continue;
      var trObject = copy_ele.clone().removeAttr("id").attr("guest_id",guests[i]["id"]);
      trObject.attr("id","guest"+guests[i]["id"]);
      this.change_name({sex:"新郎",name:guests[i].last_name+" "+guests[i].first_name},trObject);
      insert_ele.append(trObject);
      trObject.click(guest_name_click_handler);
      if(!this.deleteMode && this.is_highlight(guests[i]["id"])){
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
          if(!seats[k]["guest_id"]) continue;
          $("#table"+table_id+" .seat"+k).html(seats[k]["guest_detail"]["last_name"]+" "+seats[k]["guest_detail"]["first_name"]).attr("guest_id",seats[k]["guest_id"]);
        }
      }
    }
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
  first_higtlight:function(num){
    if(!num) num = 0;
    var guests = this.data.guests;
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
  set_seat_event:function(){
    var dataClass = this;
    var mode_click_handler = function(){
      $(".mode").removeClass("selected");
      $(this).addClass("selected");
      dataClass.deleteMode = !dataClass.deleteMode;
      if(dataClass.deleteMode){
        $("#guest_view_tbody .highlight").removeClass("highlight");
      }
      dataClass.guest_view();
    };
    $(".mode").click(mode_click_handler);
    var click_handler = function(){
      var seat_id = $(this).attr("seat_id");
      var table_id = $(this).attr("table_id");
      var guest_id = $(this).attr("guest_id");
      if(dataClass.deleteMode){
        dataClass.delete_guest_seat(table_id,seat_id,guest_id);
      }else if(guest_id){
        dataClass.replace_guest_seat(table_id,seat_id,guest_id);
      }else{
        dataClass.add_guest_to_seat(table_id,seat_id);
      }
      dataClass.table_view_all();
      dataClass.guest_view();
    };
    var click_lock = false;
    $(".seat").click(click_handler);
    var mouse_down_handler = function(){
      alert("test");
    };
    $(".seat").mousedown(mouse_down_handler);
  },
  add_guest_to_seat: function(table_id,seat_id){
    var guest_id = this.highlight_guest_id;
    this.send_data.push({state:"insert",guest_id:guest_id,seat_id:seat_id,plan_id:this.data.plan_id});
    this.set_seat_into_guest(table_id,seat_id,guest_id);
    var guest_index = this.guest_display_unset(guest_id,true);
    this.highlight_guest_id = this.first_higtlight(guest_index);
  },
  replace_guest_seat:function(table_id,seat_id,guest_id){
    this.guest_display_unset(guest_id,false);
    this.send_data.push({state:"delete",guest_id:guest_id,seat_id:seat_id,plan_id:this.data.plan_id});
    this.add_guest_to_seat(table_id,seat_id);
  },
  delete_guest_seat:function(table_id,seat_id,guest_id){
    this.guest_display_unset(guest_id,false);
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
          $("#table"+table_id+" .seat"+k).text("");
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
    var guests = this.data.guests;
    for(var i=0;i<guests.length;++i){
      if(guests[i].id == guest_id){
        return guests[i];
      }
    }
    return false;
  }
  
};