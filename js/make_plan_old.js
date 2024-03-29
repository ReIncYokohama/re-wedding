(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  if (typeof Re === "undefined" || Re === null) Re = {};

  if (Re.models == null) Re.models = {};

  if (Re.views == null) Re.views = {};

  Re.models.guest = (function(_super) {

    __extends(guest, _super);

    function guest() {
      guest.__super__.constructor.apply(this, arguments);
    }

    guest.prototype.get_sex_text = function() {
      if ((this.get("sex")) === "Male") {
        return "新郎";
      } else if ((this.get("sex")) === "Female") {
        return "新婦";
      }
      return "";
    };

    guest.prototype.get_table_text = function() {
      if (this.hasTable()) return this.get("table_name").substr(0, 2);
      return "";
    };

    guest.prototype.get_guest_image = function() {
      return "name_image/user/" + this.get("user_id") + "/guests/" + this.get("id") + "/thumb2/guest_fullname.png";
    };

    guest.prototype.get_namecard_image = function() {
      return "name_image/user/" + this.get("user_id") + "/guests/" + this.get("id") + "/namecard.png";
    };

    guest.prototype.hasTable = function() {
      if ((this.get("table_id")) === "0") return false;
      return true;
    };

    guest.prototype.get_guest_type_value = function() {
      var v;
      v = this.get("guest_type_value");
      if (v === null) return "";
      return v;
    };

    return guest;

  })(Backbone.Model);

  Re.models.usertable = (function() {

    function usertable() {}

    usertable.prototype._data = null;

    usertable.prototype.old_data = {};

    usertable.prototype.send_data = [];

    usertable.prototype.load = function(json) {
      return this._data = json;
    };

    usertable.prototype.move = function(to_seat_id, guest_id) {
      var i, seat, _ref;
      if (this.send_data.length > 0) {
        for (i = 0, _ref = this.send_data.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
          seat = this.send_data[i];
          if (Number(seat.guest_id) !== Number(guest_id)) continue;
          if (seat.state === "move") {
            this.send_data.splice(i, 1);
            break;
          }
          if (seat.state === "insert") {
            this.send_data.splice(i, 1);
            this.add(to_seat_id, guest_id);
            return;
          }
        }
      }
      return this.send_data.push({
        state: "move",
        guest_id: guest_id,
        seat_id: to_seat_id,
        plan_id: this._data.plan_id
      });
    };

    usertable.prototype.remove = function(seat_id, guest_id) {
      var i, seat, _ref;
      if (this.send_data.length > 0) {
        for (i = 0, _ref = this.send_data.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
          seat = this.send_data[i];
          if (Number(seat.guest_id) !== Number(guest_id)) continue;
          if (seat.state === "move") {
            this.send_data.splice(i, 1);
            break;
          }
          if (seat.state === "insert") {
            this.send_data.splice(i, 1);
            return;
          }
        }
      }
      return this.send_data.push({
        state: "delete",
        seat_id: seat_id,
        guest_id: guest_id,
        plan_id: this._data.plan_id
      });
    };

    usertable.prototype.add = function(seat_id, guest_id) {
      var i, seat, _ref;
      if (this.send_data.length > 0) {
        for (i = 0, _ref = this.send_data.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
          seat = this.send_data[i];
          if (Number(seat.guest_id) !== Number(guest_id)) continue;
          if (seat.state === "delete") {
            this.send_data.splice(i, 1);
            this.move(seat_id, guest_id);
            return;
          }
        }
      }
      return this.send_data.push({
        state: "insert",
        guest_id: guest_id,
        seat_id: seat_id,
        plan_id: this._data.plan_id
      });
    };

    usertable.prototype.exchange = function(from_seat_id, from_guest_id, to_seat_id, to_guest_id) {
      this.move(to_seat_id, from_guest_id);
      return this.move(from_seat_id, to_guest_id);
    };

    usertable.prototype.replace = function(seat_id, from_guest_id, to_guest_id) {
      this.add(seat_id, to_guest_id);
      return this.remove(seat_id, from_guest_id);
    };

    usertable.prototype.save = function(success) {
      return $.ajax({
        url: "save_make_plan.php",
        type: "POST",
        data: {
          data: JSON.stringify(this.send_data)
        },
        success: success
      });
    };

    usertable.prototype.sort_sex = false;

    usertable.prototype.sort_by_sex = function() {
      var guest, guests, _i, _len, _results;
      guests = _.sortBy(this._data.guests, function(obj) {
        if (obj.sex === "Male") {
          return 1;
        } else if (obj.sex === "Female") {
          return 2;
        }
        return 3;
      });
      if (this.sort_sex) {
        guests = guests.reverse();
        this.sort_sex = false;
      } else {
        this.sort_sex = true;
      }
      _results = [];
      for (_i = 0, _len = guests.length; _i < _len; _i++) {
        guest = guests[_i];
        _results.push(new Re.models.guest(guest));
      }
      return _results;
    };

    usertable.prototype.sort_guest_type = false;

    usertable.prototype.sort_by_guest_type = function() {
      var guest, guests, _i, _len, _results;
      guests = _.sortBy(this._data.guests, function(obj) {
        return obj.guest_type;
      });
      if (!this.sort_guest_type) {
        guests = guests.reverse();
        this.sort_guest_type = true;
      } else {
        this.sort_guest_type = false;
      }
      _results = [];
      for (_i = 0, _len = guests.length; _i < _len; _i++) {
        guest = guests[_i];
        _results.push(new Re.models.guest(guest));
      }
      return _results;
    };

    usertable.prototype.get_guests = function() {
      var guest, _i, _len, _ref, _results;
      _ref = this._data.guests;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        guest = _ref[_i];
        _results.push(new Re.models.guest(guest));
      }
      return _results;
    };

    usertable.prototype.sort_reset = function() {
      this.sort_sex = false;
      return this.sort_guest_type = false;
    };

    usertable.prototype.get_seat = function(seat_id) {
      var column, row, seat, _i, _j, _k, _len, _len2, _len3, _ref, _ref2, _ref3;
      _ref = this._data.rows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        _ref2 = row.columns;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          column = _ref2[_j];
          if (!column.seats) continue;
          _ref3 = column.seats;
          for (_k = 0, _len3 = _ref3.length; _k < _len3; _k++) {
            seat = _ref3[_k];
            if (seat.guest_id && seat.id === seat_id) {
              return new Re.models.guest(seat.guest_detail);
            }
          }
        }
      }
      return false;
    };

    usertable.prototype.get_table = function(seat_id) {
      var column, row, seat, _i, _j, _k, _len, _len2, _len3, _ref, _ref2, _ref3;
      _ref = this._data.rows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        _ref2 = row.columns;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          column = _ref2[_j];
          if (!column.seats) continue;
          _ref3 = column.seats;
          for (_k = 0, _len3 = _ref3.length; _k < _len3; _k++) {
            seat = _ref3[_k];
            if (seat.id === seat_id) return column;
          }
        }
      }
      return false;
    };

    usertable.prototype.is_edit = function() {
      if (this.send_data.length === 0) return false;
      return true;
    };

    return usertable;

  })();

  Re.views.make_plan = (function(_super) {

    __extends(make_plan, _super);

    function make_plan() {
      make_plan.__super__.constructor.apply(this, arguments);
    }

    make_plan.prototype.events = {
      "click #save_button": "save",
      "click #reset_button": "reset",
      "click #back_button": "back",
      "click .sort_by_sex": "sort_by_sex",
      "click .sort_by_guest_type": "sort_by_guest_type",
      "click .sort_by_reset": "sort_by_reset",
      "mouseenter .takasago_seat": "takasago_hover",
      "mouseleave .takasago_seat": "takasago_unhover",
      "mouseenter #left_sidebar_table": "left_sidebar_hover",
      "mouseleave #left_sidebar_table": "left_sidebar_unhover"
    };

    make_plan.prototype.el = "body";

    make_plan.prototype.left_sidebar = [];

    make_plan.prototype.takasago_hover = function(e) {
      var $el, guest, p;
      $el = $(e.target);
      if ($el.attr("guest_id")) {
        guest = new Re.models.guest({
          id: $el.attr("guest_id"),
          user_id: $el.attr("user_id")
        });
        this.comment_box = new Re.views.make_plan_comment_box({
          model: guest
        });
        p = $el.position();
        p.left += 25;
        return this.comment_box.near(p);
      }
    };

    make_plan.prototype.takasago_unhover = function() {
      if (this.comment_box) {
        this.comment_box.remove();
        return this.comment_box = false;
      }
    };

    make_plan.prototype.hovering_left_sidebar = false;

    make_plan.prototype.left_sidebar_hover = function() {
      this.hovering_left_sidebar = true;
      if (this.onDrag) {
        return this.$("#left_sidebar_table").css("border", "red 1px solid");
      }
    };

    make_plan.prototype.left_sidebar_unhover = function() {
      if (this.hovering_left_sidebar) {
        this.hovering_left_sidebar = false;
        return this.$("#left_sidebar_table").css("border", "none");
      }
    };

    make_plan.prototype.save = function() {
      if (confirm("修正内容を保存しても宜しいですか？")) {
        return Re.usertable.save(function() {
          return window.location.reload();
        });
      }
    };

    make_plan.prototype.reset = function() {
      if (confirm("席次表を元に戻しますか？")) return window.location.reload();
    };

    make_plan.prototype.back = function() {
      if (!Re.usertable.is_edit()) {
        window.location.href = "make_plan.php";
        return;
      }
      if (confirm("内容が変更されています。保存しても宜しいですか？")) {
        return Re.usertable.save(function() {
          return window.location.href = "make_plan.php";
        });
      }
    };

    make_plan.prototype.show_left_sidebar = function() {
      var guest, guests, i, jel, view, _ref, _results;
      guests = Re.usertable.get_guests();
      jel = $("#left_sidebar");
      _results = [];
      for (i = 0, _ref = guests.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        guest = guests[i];
        guest.set("index", i);
        view = new Re.views.make_plan_left_sidebar({
          model: guest
        });
        view.setMainView(this);
        this.left_sidebar.push(view);
        _results.push(jel.append(view.render().el));
      }
      return _results;
    };

    make_plan.prototype.sort_by_sex = function() {
      var guest, guests, i, jel, view, _ref, _results;
      guests = Re.usertable.sort_by_sex();
      jel = $("#left_sidebar");
      jel.empty();
      _results = [];
      for (i = 0, _ref = guests.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        guest = guests[i];
        guest.set("index", i);
        view = this.get_left_sidebar_by_guest_id(guest.id);
        _results.push(jel.append(view.render().el));
      }
      return _results;
    };

    make_plan.prototype.sort_by_guest_type = function() {
      var guest, guests, i, jel, view, _ref, _results;
      guests = Re.usertable.sort_by_guest_type();
      jel = $("#left_sidebar");
      jel.empty();
      _results = [];
      for (i = 0, _ref = guests.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        guest = guests[i];
        guest.set("index", i);
        view = this.get_left_sidebar_by_guest_id(guest.id);
        _results.push(jel.append(view.render().el));
      }
      return _results;
    };

    make_plan.prototype.sort_by_reset = function() {
      var guest, guests, i, jel, view, _ref;
      guests = Re.usertable.get_guests();
      jel = $("#left_sidebar");
      jel.empty();
      for (i = 0, _ref = guests.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        guest = guests[i];
        guest.set("index", i);
        view = this.get_left_sidebar_by_guest_id(guest.id);
        jel.append(view.render().el);
      }
      return Re.usertable.sort_reset();
    };

    make_plan.prototype.get_left_sidebar_by_guest_id = function(guest_id) {
      var view, _i, _len, _ref;
      _ref = this.left_sidebar;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        view = _ref[_i];
        if (view.model.get("id") === guest_id) return view;
      }
      return false;
    };

    make_plan.prototype.get_left_sidebar_refresh_by_guest_id = function(guest_id, seat_id) {
      var view;
      view = this.get_left_sidebar_by_guest_id(guest_id);
      if (view) return view.refresh(seat_id);
    };

    make_plan.prototype.set_seats = function() {
      var guest, i, seat, seat_id, seats, view, _ref, _results;
      seats = $(".seat");
      _results = [];
      for (i = 0, _ref = seats.length; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        seat = seats[i];
        seat_id = $(seat).attr("seat_id");
        guest = Re.usertable.get_seat(seat_id);
        view = new Re.views.make_plan_seat();
        view.setElement(seat);
        view.setMainView(this);
        if (guest) {
          _results.push(view.setGuest(guest));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    make_plan.prototype.set_seat_view = function(view) {
      var seat_id;
      seat_id = view.getSeatId();
      this.select_seat_id = seat_id;
      this.guest = view.guest;
      return this.seat_view = view;
    };

    make_plan.prototype.reset_seat_id = function() {
      this.select_seat_id = false;
      this.guest = false;
      return this.seat_view = false;
    };

    make_plan.prototype.get_seat_id = function() {
      return this.select_seat_id;
    };

    make_plan.prototype.drop = function(drag_view) {
      var drag_guest, seat_id;
      drag_guest = drag_view.model;
      seat_id = this.get_seat_id();
      if (this.seat_view && this.seat_view.$el) {
        this.seat_view.$el.css("border", "#A2A7BC 1px solid");
      }
      if (seat_id) {
        if (this.guest) {
          this.replace(this.guest.get("id"), drag_guest.get("id"));
          this.get_left_sidebar_refresh_by_guest_id(this.guest.get("id"));
        } else {
          this.add(drag_guest.get("id"));
        }
        this.seat_view.setGuest(drag_guest);
        return this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
      }
    };

    make_plan.prototype.drop_from_seat = function(drag_view) {
      var drag_guest, drag_seat_id, seat_id;
      if (this.seat_view && this.seat_view.$el) {
        this.seat_view.$el.css("border", "#A2A7BC 1px solid");
      }
      if (drag_view === this.seat_view) return;
      if (this.hovering_left_sidebar) {
        drag_view.remove(drag_view.getSeatId(), drag_view.guest.id);
        drag_view._remove();
        this.left_sidebar_unhover();
        return;
      }
      drag_guest = drag_view.guest;
      drag_seat_id = drag_view.getSeatId();
      seat_id = this.get_seat_id();
      if (seat_id) {
        if (this.guest) {
          this.exchange(drag_seat_id, drag_guest.get("id"), seat_id, this.guest.get("id"));
          drag_view.setGuest(this.guest);
          this.get_left_sidebar_refresh_by_guest_id(this.guest.get("id"), drag_seat_id);
        } else {
          this.move(seat_id, drag_guest.get("id"));
          drag_view._remove();
        }
        this.seat_view.setGuest(drag_guest);
        return this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
      }
    };

    make_plan.prototype.add = function(guest_id) {
      return Re.usertable.add(this.get_seat_id(), guest_id);
    };

    make_plan.prototype.remove = function(seat_id, guest_id) {
      Re.usertable.remove(seat_id, guest_id);
      return this.get_left_sidebar_refresh_by_guest_id(guest_id);
    };

    make_plan.prototype.move = function(seat_id, guest_id) {
      return Re.usertable.move(seat_id, guest_id);
    };

    make_plan.prototype.exchange = function(from_seat_id, from_guest_id, to_seat_id, to_guest_id) {
      return Re.usertable.exchange(from_seat_id, from_guest_id, to_seat_id, to_guest_id);
    };

    make_plan.prototype.replace = function(from_guest_id, to_guest_id) {
      return Re.usertable.replace(this.get_seat_id(), from_guest_id, to_guest_id);
    };

    make_plan.prototype.onDrag = false;

    return make_plan;

  })(Backbone.View);

  Re.views.make_plan_view = (function(_super) {

    __extends(make_plan_view, _super);

    function make_plan_view() {
      this.drag = __bind(this.drag, this);
      this.move = __bind(this.move, this);
      make_plan_view.__super__.constructor.apply(this, arguments);
    }

    make_plan_view.prototype.move = function() {
      var html_height, html_width, win, win_left, win_top;
      if (!this.screen_x) return;
      win = $(window);
      win_left = win.scrollLeft();
      win_top = win.scrollTop();
      html_width = $("html").width();
      html_height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
      if (this.screen_x + 150 > html_width) {
        win.scrollLeft(win_left + 5);
      } else if (this.screen_x < 150 && win_left > 0) {
        win.scrollLeft(win_left - 5);
      }
      if (this.screen_y + 50 > html_height) {
        return win.scrollTop(win_top + 5);
      } else if (this.screen_y < 200 && win_top > 0) {
        return win.scrollTop(win_top - 5);
      }
    };

    make_plan_view.prototype.screen_x = 0;

    make_plan_view.prototype.drag = function(e) {
      this.dragbox.near(e);
      this.screen_x = e.screenX;
      return this.screen_y = e.screenY;
    };

    make_plan_view.prototype.setMainView = function(view) {
      return this.main_view = view;
    };

    make_plan_view.prototype._hover = function(guest_model, position) {
      if (position == null) position = this.$el.position();
      if (!this.main_view.onDrag) {
        if (guest_model) {
          this.comment_box = new Re.views.make_plan_comment_box({
            model: guest_model
          });
          this.comment_box.near(position);
        }
      }
    };

    make_plan_view.prototype._unhover = function() {
      if (this.comment_box) {
        this.comment_box.remove();
        return this.comment_box = false;
      }
    };

    make_plan_view.prototype.unhover = function() {
      return this._unhover();
    };

    make_plan_view.prototype._dragstart = function(guest_model, e) {
      if (this.comment_box) {
        this.comment_box.remove();
        this.comment_box = false;
      }
      this.main_view.onDrag = true;
      this.dragbox = new Re.views.make_plan_drag_box({
        model: guest_model
      });
      this.dragevent = $("body").bind("mousemove", this.drag);
      this.mouseupevent = $("body").bind("mouseup", this.mouseup);
      this.timer = setInterval(this.move, 20);
      return e.originalEvent.preventDefault();
    };

    make_plan_view.prototype._mouseup = function() {
      if (this.timer) {
        clearInterval(this.timer);
        this.timer = false;
      }
      $("body").unbind("mousemove", this.drag);
      $("body").unbind("mouseup", this.mouseup);
      this.dragbox.remove();
      this.main_view.onDrag = false;
      return this.main_view.reset_seat_id();
    };

    return make_plan_view;

  })(Backbone.View);

  Re.views.make_plan_seat = (function(_super) {

    __extends(make_plan_seat, _super);

    function make_plan_seat() {
      this.mouseup = __bind(this.mouseup, this);
      make_plan_seat.__super__.constructor.apply(this, arguments);
    }

    make_plan_seat.prototype.events = {
      "mouseenter": "hover",
      "mouseleave": "unhover",
      "dblclick": "remove",
      "mousedown": "dragstart"
    };

    make_plan_seat.prototype.hover = function() {
      this._hover(this.guest);
      if (this.main_view.onDrag) {
        this.$el.css("border", "red 1px solid");
        return this.main_view.set_seat_view(this);
      }
    };

    make_plan_seat.prototype.unhover = function() {
      this._unhover();
      if (this.main_view.onDrag) {
        this.$el.css("border", "#A2A7BC 1px solid");
        return this.main_view.reset_seat_id();
      }
    };

    make_plan_seat.prototype.setGuest = function(guest) {
      this.guest = guest;
      return this.$el.css({
        "background-image": "url(" + guest.get_guest_image() + ")",
        "background-repeat": "no-repeat",
        "background-position": "0px 50%"
      });
    };

    make_plan_seat.prototype.getSeatId = function() {
      return this.$el.attr("seat_id");
    };

    make_plan_seat.prototype.remove = function() {
      if (!this.guest) return;
      this.main_view.remove(this.getSeatId(), this.guest.id);
      return this._remove();
    };

    make_plan_seat.prototype._remove = function() {
      this.$el.css("background-image", "none");
      return this.guest = null;
    };

    make_plan_seat.prototype.dragstart = function(e) {
      if (!this.guest) return;
      return this._dragstart(this.guest, e);
    };

    make_plan_seat.prototype.mouseup = function() {
      this.main_view.drop_from_seat(this);
      return this._mouseup();
    };

    return make_plan_seat;

  })(Re.views.make_plan_view);

  Re.views.make_plan_left_sidebar = (function(_super) {

    __extends(make_plan_left_sidebar, _super);

    function make_plan_left_sidebar() {
      this.mouseup = __bind(this.mouseup, this);
      this.dragstart = __bind(this.dragstart, this);
      make_plan_left_sidebar.__super__.constructor.apply(this, arguments);
    }

    make_plan_left_sidebar.prototype.tagName = "tr";

    make_plan_left_sidebar.prototype.events = {
      "mousedown .name": "dragstart",
      "mouseenter .name": "hover",
      "mouseleave .name": "unhover"
    };

    make_plan_left_sidebar.prototype.template = function(model, i) {
      var html;
      html = "<td class=\"no\">" + i + "</td>" + "<td class=\"sex\">" + model.get_sex_text() + "</td>" + "<td class=\"group\">" + model.get_guest_type_value() + "</td>" + "<td class=\"name\" style=\"background-image:url(" + model.get_guest_image() + ");\"></td>" + "<td class=\"tablename\">" + model.get_table_text() + "</td>";
      return _.template(html);
    };

    make_plan_left_sidebar.prototype.render = function() {
      this.$el.html(this.template(this.model, this.model.get("index")));
      if (this.model.hasTable()) this.color_on_table();
      this.delegateEvents();
      return this;
    };

    make_plan_left_sidebar.prototype.hover = function() {
      var p;
      p = this.$el.position();
      p.left += 100;
      return this._hover(this.model, p);
    };

    make_plan_left_sidebar.prototype.dragstart = function(e) {
      if (this.model.hasTable()) return;
      return this._dragstart(this.model, e);
    };

    make_plan_left_sidebar.prototype.mouseup = function(e) {
      this.main_view.drop(this);
      return this._mouseup();
    };

    make_plan_left_sidebar.prototype.color_on_table = function() {
      return this.$el.css({
        "background": "lightgrey"
      });
    };

    make_plan_left_sidebar.prototype.color_off_table = function() {
      return this.$el.css({
        "background": "none"
      });
    };

    make_plan_left_sidebar.prototype.refresh = function(seat_id) {
      var column;
      if (seat_id == null) seat_id = false;
      if (!seat_id) {
        this.model.set("seat_id", false);
        this.model.set("table_id", "0");
        this.model.set("table_name", "");
        this.$(".tablename").html("");
        this.color_off_table();
        return;
      }
      this.color_on_table();
      this.model.set("seat_id", seat_id);
      column = Re.usertable.get_table(seat_id);
      this.model.set("table_id", column.table_id);
      this.model.set("table_name", column.name);
      return this.$(".tablename").html(this.model.get_table_text());
    };

    return make_plan_left_sidebar;

  })(Re.views.make_plan_view);

  Re.views.make_plan_drag_box = (function(_super) {

    __extends(make_plan_drag_box, _super);

    function make_plan_drag_box() {
      make_plan_drag_box.__super__.constructor.apply(this, arguments);
    }

    make_plan_drag_box.prototype.tagName = "div";

    make_plan_drag_box.prototype.className = "drag_box";

    make_plan_drag_box.prototype.initialize = function() {
      this.$el.html("<img src=\"" + this.model.get_guest_image() + "\"/>");
      this.$el.hide();
      return $("body").append(this.el);
    };

    make_plan_drag_box.prototype.near = function(e) {
      this.$el.show();
      return this.$el.css({
        top: (e.pageY + 10) + "px",
        left: (e.pageX - 30) + "px"
      });
    };

    make_plan_drag_box.prototype.remove = function() {
      return this.$el.remove();
    };

    return make_plan_drag_box;

  })(Backbone.View);

  Re.views.make_plan_comment_box = (function(_super) {

    __extends(make_plan_comment_box, _super);

    function make_plan_comment_box() {
      make_plan_comment_box.__super__.constructor.apply(this, arguments);
    }

    make_plan_comment_box.prototype.tagName = "div";

    make_plan_comment_box.prototype.className = "drag_box";

    make_plan_comment_box.prototype.initialize = function() {
      if (!this.model) return;
      this.$el.html("<img src=\"" + this.model.get_namecard_image() + "\"/>");
      this.$el.hide();
      return $("body").append(this.el);
    };

    make_plan_comment_box.prototype.near = function(p) {
      this.$el.show();
      return this.$el.css({
        top: (p.top + 40) + "px",
        left: (p.left - 45) + "px"
      });
    };

    make_plan_comment_box.prototype.remove = function() {
      return this.$el.remove();
    };

    return make_plan_comment_box;

  })(Backbone.View);

  Re.usertable = new Re.models.usertable();

}).call(this);
