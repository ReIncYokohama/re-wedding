# models
#  guest ゲストのオブジェクトを格納すると、招待客の画像urlなどを返す
#  usertable テーブルデータの変更を格納し、まとめて保存するためのツール
#
#
#
Re ?= {}
Re.models ?= {}
Re.views ?= {}

class Re.models.guest extends Backbone.Model
  get_sex_text:()->
    if (@get "sex") is "Male"
      return "新郎"
    else if (@get "sex") is "Female"
      return "新婦"
    return ""
  get_table_text:()->
    if @hasTable()
      return @get("table_name").substr(0,2)
    return ""
  get_guest_image:()->
    return "name_image/user/"+@get("user_id")+"/guests/"+@get("id")+"/thumb2/guest_fullname.png"
  hasTable:()->
    if (@get "table_id") is "0"
      return false
    return true
  get_guest_type_value:()->
    v = @.get "guest_type_value"
    if v is null
      return ""
    return v

class Re.models.usertable
  _data:null
  old_data: {}
  send_data:[]
  load:(json)->
    @_data = json
  move:(to_seat_id,guest_id)->
    this.send_data.push
      state:"move"
      guest_id:guest_id
      seat_id:to_seat_id
      plan_id:this._data.plan_id
  remove:(seat_id,guest_id)->
    this.send_data.push
      state:"delete"
      seat_id:seat_id
      guest_id:guest_id
      plan_id:this._data.plan_id
  add:(seat_id,guest_id)->
    this.send_data.push
      state:"insert"
      guest_id:guest_id
      seat_id:seat_id
      plan_id:this._data.plan_id
  #ゲストを交換(席同士)
  exchange:(from_seat_id,from_guest_id,to_seat_id,to_guest_id)->
    @move to_seat_id,from_guest_id
    @move from_seat_id,to_guest_id
  #ゲストを交換(リスト)
  replace:(seat_id,from_guest_id,to_guest_id)->
    @add seat_id,to_guest_id
    @remove seat_id,from_guest_id
  save:(success)->
    $.ajax
      url:"save_make_plan.php"
      type:"POST"
      data:
        data:JSON.stringify @send_data
      success:success
  get_guests:()->
    (new Re.models.guest guest for guest in @_data.guests)
  get_seat:(seat_id)->
    for row in @_data.rows
      for column in row.columns
        if not column.seats
          continue
        for seat in column.seats
          if seat.guest_id and seat.id is seat_id
            return new Re.models.guest seat.guest_detail
    return false
  get_table:(seat_id)->
    for row in @_data.rows
      for column in row.columns
        if not column.seats
          continue
        for seat in column.seats
          if seat.id is seat_id
            return column
    return false
  is_edit:()->
    if @send_data.length is 0
      return false
    return true

class Re.views.make_plan extends Backbone.View
  events:
    "click #save_button":"save"
    "click #reset_button":"reset"
    "click #back_button":"back"
  el:"body"
  left_sidebar:[]
  save:()->
    if confirm "修正内容を保存しても宜しいですか？"
      Re.usertable.save ->
        window.location.reload()
  reset:()->
    if confirm "席次表を元に戻しますか？"
      window.location.reload()
  back:()->
    if not Re.usertable.is_edit()
      window.location.href = "make_plan.php"
      return
    if confirm "内容が変更されています。保存しても宜しいですか？"
      Re.usertable.save ->
        window.location.href = "make_plan.php"
    else
      window.location.href = "make_plan.php"
  show_left_sidebar:()->
    guests = Re.usertable.get_guests()
    jel = $("#left_sidebar")
    for i in [0..guests.length-1]
      guest = guests[i]
      guest.set "index",i
      view = new Re.views.make_plan_left_sidebar
        model:guest
      view.setMainView @
      @left_sidebar.push view
      jel.append view.render().el
  get_left_sidebar_by_guest_id:(guest_id)->
    for view in @left_sidebar
      if view.model.get("id") is guest_id
        return view
    return false
  get_left_sidebar_refresh_by_guest_id:(guest_id,seat_id)->
    view = @get_left_sidebar_by_guest_id guest_id
    if view
      view.refresh(seat_id)
  set_seats:()->
    seats = $(".seat")
    for i in [0..seats.length]
      seat = seats[i]
      seat_id = $(seat).attr "seat_id"
      guest = Re.usertable.get_seat seat_id
      view = new Re.views.make_plan_seat()
      view.setElement seat
      view.setMainView @
      if guest
        view.setGuest guest
  set_seat_view:(view)->
    seat_id = view.getSeatId()
    @select_seat_id = seat_id
    @guest = view.guest
    @seat_view = view
  reset_seat_id:()->
    @select_seat_id = false
    @guest = false
    @seat_view = false
  get_seat_id:()->
    return @select_seat_id
  drop:(drag_view)->
    drag_guest = drag_view.model
    seat_id = @get_seat_id()
    if seat_id
      #add or replace
      if @guest
        @replace @guest.get("id"),drag_guest.get("id")
        @seat_view.setGuest drag_guest
        @get_left_sidebar_refresh_by_guest_id drag_guest.get("id"),seat_id
        @get_left_sidebar_refresh_by_guest_id @guest.get("id")
      else
        @add drag_guest.get("id")
        @seat_view.setGuest drag_guest
        @get_left_sidebar_refresh_by_guest_id drag_guest.get("id"),seat_id
  drop_from_seat:(drag_view)->
    if drag_view is @seat_view
      return
    drag_guest = drag_view.guest
    drag_seat_id = drag_view.getSeatId()
    seat_id = @get_seat_id()
    if seat_id
      #move or exchange
      if @guest
        @exchange drag_seat_id,drag_guest.get("id"),seat_id,@guest.get("id")
        @seat_view.setGuest drag_guest
        drag_view.setGuest @guest
        @get_left_sidebar_refresh_by_guest_id @guest.get("id"),drag_seat_id
        @get_left_sidebar_refresh_by_guest_id drag_guest.get("id"),seat_id
      else
        @move seat_id,drag_guest.get("id")
        @seat_view.setGuest drag_guest
        drag_view._remove()
        @get_left_sidebar_refresh_by_guest_id drag_guest.get("id"),seat_id
  add:(guest_id)->
    Re.usertable.add @get_seat_id(),guest_id
  remove:(seat_id,guest_id)->
    Re.usertable.remove seat_id,guest_id
    @get_left_sidebar_refresh_by_guest_id guest_id
  move:(seat_id,guest_id)->
    Re.usertable.move seat_id,guest_id
  exchange:(from_seat_id,from_guest_id,to_seat_id,to_guest_id)->
    Re.usertable.exchange from_seat_id,from_guest_id,to_seat_id,to_guest_id
  replace:(from_guest_id,to_guest_id)->
    Re.usertable.replace @get_seat_id(),from_guest_id,to_guest_id
  onDrag:false

class Re.views.make_plan_view extends Backbone.View
  move:()=>
    if not @screen_x
      return
    win = $(window)
    win_left = win.scrollLeft()
    win_top = win.scrollTop()
    html_width = $("html").width()
    html_height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    if @screen_x + 150 > html_width
      win.scrollLeft(win_left + 5)
    else if @screen_x < 150 and win_left > 0
      win.scrollLeft(win_left - 5)
    if @screen_y + 50 > html_height
      win.scrollTop(win_top + 5)
    else if @screen_y < 200 and win_top > 0
      win.scrollTop(win_top - 5)
  screen_x:0
  drag:(e)=>
    @dragbox.near(e)
    @screen_x = e.screenX
    @screen_y = e.screenY


class Re.views.make_plan_seat extends Re.views.make_plan_view
  events:
    "mouseenter":"hover"
    "mouseleave":"unhover"
    "dblclick":"remove"
    "mousedown":"dragstart"
  hover:()->
    if not @main_view.onDrag
      return
    @$el.css "border","red 1px solid"
    @main_view.set_seat_view @
  unhover:()->
    @$el.css "border","#A2A7BC 1px solid"
    @main_view.reset_seat_id()
  setMainView:(view)->
    @main_view = view
  setGuest:(guest)->
    @guest = guest
    @$el.css
      "background-image":"url("+guest.get_guest_image()+")"
      "background-repeat":"no-repeat"
      "background-position":"0px 50%"
  getSeatId:()->
    return @$el.attr "seat_id"
  remove:()->
    if not @guest
      return
    @main_view.remove @getSeatId(),@guest.id
    @_remove()
  _remove:()->
    @$el.css "background-image","none"
    @guest = null
  dragstart:()->
    if not @guest
      return
    @main_view.onDrag = true
    @dragbox = new Re.views.make_plan_drag_box(
      model:@guest
    )
    @dragevent = $("body").bind "mousemove",@drag
    @mouseupevent = $("body").bind "mouseup",@mouseup
    @timer = setInterval @move,20
    e.originalEvent.preventDefault()
  mouseup:()=>
    if @timer
      clearInterval @timer
      @timer = false
    $("body").unbind "mousemove",@drag
    $("body").unbind "mouseup",@mouseup
    @dragbox.remove()
    @main_view.onDrag = false
    @main_view.drop_from_seat @

class Re.views.make_plan_left_sidebar extends Re.views.make_plan_view
  tagName:"tr"
  events:
    "mousedown .name":"dragstart"
  template:(model,i)->
    html = "<td class=\"no\">"+i+"</td>"+"<td class=\"sex\">"+model.get_sex_text()+"</td>"+
      "<td class=\"group\">"+model.get_guest_type_value()+"</td>"+
      "<td class=\"name\" style=\"background-image:url("+model.get_guest_image()+");\"></td>"+
      "<td class=\"tablename\">"+model.get_table_text()+"</td>"
    _.template html
  render:()->
    @$el.html(@template @model,@model.get "index")
    @delegateEvents()
    @
  setMainView:(view)->
    @main_view = view
  dragstart:(e)=>
    if @model.hasTable()
      return
    @main_view.onDrag = true
    @dragbox = new Re.views.make_plan_drag_box(
      model:@model
    )
    @dragevent = $("body").bind "mousemove",@drag
    @mouseupevent = $("body").bind "mouseup",@mouseup
    @timer = setInterval @move,20
    e.originalEvent.preventDefault()

  mouseup:(e)=>
    if @timer
      clearInterval @timer
      @timer = false
    $("body").unbind "mousemove",@drag
    $("body").unbind "mouseup",@mouseup
    @dragbox.remove()
    @main_view.onDrag = false
    @main_view.drop @
  refresh:(seat_id = false)->
    if not seat_id
      @model.set "seat_id",false
      @model.set "table_id","0"
      @model.set "table_name",""
      @.$(".tablename").html ""
      return
    @model.set "seat_id",seat_id
    column = Re.usertable.get_table seat_id
    @model.set "table_id",column.table_id
    @model.set "table_name",column.name
    @.$(".tablename").html @model.get_table_text()

class Re.views.make_plan_drag_box extends Backbone.View
  tagName:"div"
  className:"drag_box"
  initialize:()->
    @$el.html "<img src=\""+@model.get_guest_image()+"\"/>";
    @$el.hide()
    $("body").append @el
  near:(e)->
    @$el.show()
    @$el.css
      top:(e.pageY+10)+"px"
      left:(e.pageX-30)+"px"
  remove:()->
    @$el.remove()

Re.usertable = new Re.models.usertable()