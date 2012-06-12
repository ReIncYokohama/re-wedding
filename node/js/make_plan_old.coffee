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
  get_namecard_image:()->
    return "name_image/user/"+@get("user_id")+"/guests/"+@get("id")+"/namecard.png"
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
    if @send_data.length > 0
      for i in [0..@send_data.length-1]
        seat = @send_data[i]
        if Number(seat.guest_id) isnt Number(guest_id)
          continue
        #更新先と更新元が同じ場合はサーバーサイドで更新しない処理をしている
        if seat.state is "move"
          @send_data.splice(i,1)
          break
        if seat.state is "insert"
          @send_data.splice(i,1)
          @add to_seat_id,guest_id
          return
    @send_data.push
      state:"move"
      guest_id:guest_id
      seat_id:to_seat_id
      plan_id:this._data.plan_id
  remove:(seat_id,guest_id)->
    #もし前にinsert や changeがあれば削除する
    if @send_data.length > 0
      for i in [0..@send_data.length-1]
        seat = @send_data[i]
        if Number(seat.guest_id) isnt Number(guest_id)
          continue
        if seat.state is "move"
          @send_data.splice(i,1)
          break
        if seat.state is "insert"
          @send_data.splice(i,1)
          @send_data = @send_data.splice i,1
          return
    @send_data.push
      state:"delete"
      seat_id:seat_id
      guest_id:guest_id
      plan_id:this._data.plan_id
  add:(seat_id,guest_id)->
    #もし前にdeleteがあれば削除する
    if @send_data.length > 0
      for i in [0..@send_data.length-1]
        seat = @send_data[i]
        if Number(seat.guest_id) isnt Number(guest_id)
          continue
        #更新先と更新元が同じ場合はサーバーサイドで更新しない処理をしている
        if seat.state is "delete"
          @send_data.splice(i,1)
          @move seat_id,guest_id
          return
    @send_data.push
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
  sort_sex:false
  sort_by_sex:()->
    guests = _.sortBy @_data.guests,(obj)->
      if obj.sex is"Male"
        return 1
      else if obj.sex is "Female"
        return 2
      return 3
    if @sort_sex
      guests =  guests.reverse()
      @sort_sex = false
    else
      @sort_sex = true
    (new Re.models.guest guest for guest in guests)
  sort_guest_type:false
  sort_by_guest_type:()->
    guests = _.sortBy @_data.guests,(obj)->
      return obj.guest_type
    if not @sort_guest_type
      guests =  guests.reverse()
      @sort_guest_type = true
    else
      @sort_guest_type = false
    (new Re.models.guest guest for guest in guests)
  get_guests:()->
    (new Re.models.guest guest for guest in @_data.guests)
  sort_reset:()->
    @sort_sex = false
    @sort_guest_type = false
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
    "click .sort_by_sex":"sort_by_sex"
    "click .sort_by_guest_type":"sort_by_guest_type"
    "click .sort_by_reset":"sort_by_reset"
    "mouseenter .takasago_seat":"takasago_hover"
    "mouseleave .takasago_seat":"takasago_unhover"
    "mouseenter #left_sidebar_table":"left_sidebar_hover"
    "mouseleave #left_sidebar_table":"left_sidebar_unhover"
  el:"body"
  left_sidebar:[]

  #高砂席をホバーしたときにゲストの詳細情報が分かる
  takasago_hover:(e)->
    $el = $(e.target)
    if $el.attr "guest_id"
      guest = new Re.models.guest
        id:$el.attr "guest_id"
        user_id:$el.attr "user_id"
      @comment_box = new Re.views.make_plan_comment_box(
        model:guest
      )
      p = $el.position()
      p.left += 25
      @comment_box.near(p)
  takasago_unhover:()->
    if @comment_box
      @comment_box.remove()
      @comment_box = false

  #サイドバーをホバーしているかどうか
  hovering_left_sidebar:false

  left_sidebar_hover:()->
    @hovering_left_sidebar = true
    if @onDrag
      @$("#left_sidebar_table").css "border","red 1px solid"

  left_sidebar_unhover:()->
    if @hovering_left_sidebar
      @hovering_left_sidebar = false
      @$("#left_sidebar_table").css "border","none"

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

  #ソート機能
  sort_by_sex:()->
    guests = Re.usertable.sort_by_sex()
    jel = $("#left_sidebar")
    jel.empty()
    for i in [0..guests.length-1]
      guest = guests[i]
      guest.set "index",i
      view = @get_left_sidebar_by_guest_id guest.id
      jel.append view.render().el
  sort_by_guest_type:()->
    guests = Re.usertable.sort_by_guest_type()
    jel = $("#left_sidebar")
    jel.empty()
    for i in [0..guests.length-1]
      guest = guests[i]
      guest.set "index",i
      view = @get_left_sidebar_by_guest_id guest.id
      jel.append view.render().el
  sort_by_reset:()->
    guests = Re.usertable.get_guests()
    jel = $("#left_sidebar")
    jel.empty()
    for i in [0..guests.length-1]
      guest = guests[i]
      guest.set "index",i
      view = @get_left_sidebar_by_guest_id guest.id
      jel.append view.render().el
    Re.usertable.sort_reset()

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
    if @seat_view and @seat_view.$el
      @seat_view.$el.css "border","#A2A7BC 1px solid"
    if seat_id
      #add or replace
      if @guest
        @replace @guest.get("id"),drag_guest.get("id")
        @get_left_sidebar_refresh_by_guest_id @guest.get("id")
      else
        @add drag_guest.get("id")
      @seat_view.setGuest drag_guest
      @get_left_sidebar_refresh_by_guest_id drag_guest.get("id"),seat_id

  #drag_viewが今ドラッグしているview seat_viewが今ホバーしているview
  drop_from_seat:(drag_view)->
    if @seat_view and @seat_view.$el
      @seat_view.$el.css "border","#A2A7BC 1px solid"
    #同じシートにドロップしていた場合、終わり
    if drag_view is @seat_view
      return
    #レフトサイドバーにドラッグした場合、元に戻す
    if @hovering_left_sidebar
      drag_view.remove drag_view.getSeatId(),drag_view.guest.id
      drag_view._remove()
      @left_sidebar_unhover()
      return
    drag_guest = drag_view.guest
    drag_seat_id = drag_view.getSeatId()
    seat_id = @get_seat_id()
    if seat_id
      #move or exchange
      if @guest
        @exchange drag_seat_id,drag_guest.get("id"),seat_id,@guest.get("id")
        drag_view.setGuest @guest
        @get_left_sidebar_refresh_by_guest_id @guest.get("id"),drag_seat_id
      else
        @move seat_id,drag_guest.get("id")
        drag_view._remove()
      @seat_view.setGuest drag_guest
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


#座席表および
class Re.views.make_plan_view extends Backbone.View
  move:()=>
    if not @screen_x
      return
    win = $(window)
    win_left = win.scrollLeft()
    win_top = win.scrollTop()
    html_width = $("html").width()
    html_height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    #screen_x
    if @screen_x + 150 > html_width
      win.scrollLeft(win_left + 5)
    #screen_x < 600
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
  setMainView:(view)->
    @main_view = view
  _hover:(guest_model,position = @$el.position())->
    if not @main_view.onDrag
      if guest_model
        @comment_box = new Re.views.make_plan_comment_box(
          model:guest_model
        )
        @comment_box.near(position)
      return
  _unhover:()->
    if @comment_box
      @comment_box.remove()
      @comment_box = false
  unhover:()->
    @_unhover()
  _dragstart:(guest_model,e)->
    if @comment_box
      @comment_box.remove()
      @comment_box = false
    @main_view.onDrag = true
    @dragbox = new Re.views.make_plan_drag_box(
      model:guest_model
    )
    @dragevent = $("body").bind "mousemove",@drag
    @mouseupevent = $("body").bind "mouseup",@mouseup
    @timer = setInterval @move,20
    e.originalEvent.preventDefault()
  _mouseup:()->
    if @timer
      clearInterval @timer
      @timer = false
    $("body").unbind "mousemove",@drag
    $("body").unbind "mouseup",@mouseup
    @dragbox.remove()
    @main_view.onDrag = false
    @main_view.reset_seat_id()


#座席表に配席しているシートのview
class Re.views.make_plan_seat extends Re.views.make_plan_view
  events:
    "mouseenter":"hover"
    "mouseleave":"unhover"
    "dblclick":"remove"
    "mousedown":"dragstart"

  #ドラッグ中にホバーしたシートに色を塗る
  hover:()->
    @_hover @guest
    if @main_view.onDrag
      @$el.css "border","red 1px solid"
      @main_view.set_seat_view @
  unhover:()->
    @_unhover()
    if @main_view.onDrag
      @$el.css "border","#A2A7BC 1px solid"
      @main_view.reset_seat_id()

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
  #シートを削除
  _remove:()->
    @$el.css "background-image","none"
    @guest = null
  dragstart:(e)->
    if not @guest
      return
    @_dragstart(@guest,e)
  mouseup:()=>
    @main_view.drop_from_seat @
    @_mouseup()

#左のサイドバーの招待客一覧のひとつひとつの招待客のビュー
class Re.views.make_plan_left_sidebar extends Re.views.make_plan_view
  tagName:"tr"
  events:
    "mousedown .name":"dragstart"
    "mouseenter .name":"hover"
    "mouseleave .name":"unhover"
  template:(model,i)->
    html = "<td class=\"no\">"+i+"</td>"+"<td class=\"sex\">"+model.get_sex_text()+"</td>"+
      "<td class=\"group\">"+model.get_guest_type_value()+"</td>"+
      "<td class=\"name\" style=\"background-image:url("+model.get_guest_image()+");\"></td>"+
      "<td class=\"tablename\">"+model.get_table_text()+"</td>"
    _.template html
  render:()->
    @$el.html(@template @model,@model.get "index")
    if @model.hasTable()
      @color_on_table()
    @delegateEvents()
    @
  hover:()->
    p = @$el.position()
    p.left += 100
    @_hover @model,p
  dragstart:(e)=>
    if @model.hasTable()
      return
    @_dragstart(@model,e)
  mouseup:(e)=>
    @main_view.drop @
    @_mouseup()
  color_on_table:()->
    @$el.css
      "background":"lightgrey"
  color_off_table:()->
    @$el.css
      "background":"none"
  refresh:(seat_id = false)->
    if not seat_id
      @model.set "seat_id",false
      @model.set "table_id","0"
      @model.set "table_name",""
      @.$(".tablename").html ""
      @color_off_table()
      return
    @color_on_table()
    @model.set "seat_id",seat_id
    column = Re.usertable.get_table seat_id
    @model.set "table_id",column.table_id
    @model.set "table_name",column.name
    @.$(".tablename").html @model.get_table_text()

#ドラッグ中に表示する招待客のビュー
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

#ホバー中に表示する招待客のビュー
class Re.views.make_plan_comment_box extends Backbone.View
  tagName:"div"
  className:"drag_box"
  initialize:()->
    if not @model
      return
    @$el.html "<img src=\""+@model.get_namecard_image()+"\"/>";
    @$el.hide()
    $("body").append @el
  near:(p)->
    @$el.show()
    @$el.css
      top:(p.top+40)+"px"
      left:(p.left-45)+"px"
  remove:()->
    @$el.remove()

Re.usertable = new Re.models.usertable()