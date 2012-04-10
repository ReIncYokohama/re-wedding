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
      if (this.hasTable()) return this.get("table_name");
      return "";
    };

    guest.prototype.get_guest_image = function() {
      return "name_image/user/" + this.get("user_id") + "/guests/" + this.get("id") + "/thumb2/guest_fullname.png";
    };

    guest.prototype.hasTable = function() {
      if ((this.get("table_id")) === "0") return false;
      return true;
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
      return this.send_data.push({
        state: "move",
        guest_id: guest_id,
        seat_id: to_seat_id,
        plan_id: this._data.plan_id
      });
    };

    usertable.prototype.remove = function(seat_id, guest_id) {
      return this.send_data.push({
        state: "delete",
        seat_id: seat_id,
        guest_id: guest_id,
        plan_id: this._data.plan_id
      });
    };

    usertable.prototype.add = function(seat_id, guest_id) {
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

    usertable.prototype.get_seat = function(seat_id) {
      var column, row, seat, _i, _j, _k, _len, _len2, _len3, _ref, _ref2, _ref3;
      _ref = this._data.rows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        _ref2 = row.columns;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          column = _ref2[_j];
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
          _ref3 = column.seats;
          for (_k = 0, _len3 = _ref3.length; _k < _len3; _k++) {
            seat = _ref3[_k];
            if (seat.id === seat_id) return column;
          }
        }
      }
      return false;
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
      "click #back_button": "back"
    };

    make_plan.prototype.el = "body";

    make_plan.prototype.left_sidebar = [];

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
      if (confirm("内容が変更されています。保存しても宜しいですか？")) {
        return Re.usertable.save(function() {
          return window.location.href = "make_plan.php";
        });
      } else {
        return window.location.href = "make_plan.php";
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
      var guest, seat, seat_id, seats, view, _i, _len, _results;
      seats = $(".seat");
      _results = [];
      for (_i = 0, _len = seats.length; _i < _len; _i++) {
        seat = seats[_i];
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
      if (seat_id) {
        if (this.guest) {
          this.replace(this.guest.get("id"), drag_guest.get("id"));
          this.seat_view.setGuest(drag_guest);
          this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
          return this.get_left_sidebar_refresh_by_guest_id(this.guest.get("id"));
        } else {
          this.add(drag_guest.get("id"));
          this.seat_view.setGuest(drag_guest);
          return this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
        }
      }
    };

    make_plan.prototype.drop_from_seat = function(drag_view) {
      var drag_guest, drag_seat_id, seat_id;
      if (drag_view === this.seat_view) return;
      drag_guest = drag_view.guest;
      drag_seat_id = drag_view.getSeatId();
      seat_id = this.get_seat_id();
      if (seat_id) {
        if (this.guest) {
          this.exchange(drag_seat_id, drag_guest.get("id"), seat_id, this.guest.get("id"));
          this.seat_view.setGuest(drag_guest);
          drag_view.setGuest(this.guest);
          this.get_left_sidebar_refresh_by_guest_id(this.guest.get("id"), drag_seat_id);
          return this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
        } else {
          this.move(seat_id, drag_guest.get("id"));
          this.seat_view.setGuest(drag_guest);
          drag_view._remove();
          return this.get_left_sidebar_refresh_by_guest_id(drag_guest.get("id"), seat_id);
        }
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

  Re.views.make_plan_seat = (function(_super) {

    __extends(make_plan_seat, _super);

    function make_plan_seat() {
      this.mouseup = __bind(this.mouseup, this);
      this.drag = __bind(this.drag, this);
      make_plan_seat.__super__.constructor.apply(this, arguments);
    }

    make_plan_seat.prototype.events = {
      "mouseenter": "hover",
      "mouseleave": "unhover",
      "dblclick": "remove",
      "mousedown": "dragstart"
    };

    make_plan_seat.prototype.hover = function() {
      if (!this.main_view.onDrag) return;
      this.$el.css("border", "red 1px solid");
      return this.main_view.set_seat_view(this);
    };

    make_plan_seat.prototype.unhover = function() {
      this.$el.css("border", "#A2A7BC 1px solid");
      return this.main_view.reset_seat_id();
    };

    make_plan_seat.prototype.setMainView = function(view) {
      return this.main_view = view;
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
      this.$el.css("background-image", "initial");
      return this.guest = null;
    };

    make_plan_seat.prototype.dragstart = function() {
      if (!this.guest) return;
      this.main_view.onDrag = true;
      this.dragbox = new Re.views.make_plan_drag_box({
        model: this.guest
      });
      this.dragevent = $(window).bind("mousemove", this.drag);
      return this.mouseupevent = $(window).bind("mouseup", this.mouseup);
    };

    make_plan_seat.prototype.drag = function(e) {
      return this.dragbox.near(e);
    };

    make_plan_seat.prototype.mouseup = function() {
      $(window).unbind("mousemove", this.drag);
      $(window).unbind("mouseup", this.mouseup);
      this.dragbox.remove();
      this.main_view.onDrag = false;
      return this.main_view.drop_from_seat(this);
    };

    return make_plan_seat;

  })(Backbone.View);

  Re.views.make_plan_left_sidebar = (function(_super) {

    __extends(make_plan_left_sidebar, _super);

    function make_plan_left_sidebar() {
      this.mouseup = __bind(this.mouseup, this);
      this.drag = __bind(this.drag, this);
      this.dragstart = __bind(this.dragstart, this);
      make_plan_left_sidebar.__super__.constructor.apply(this, arguments);
    }

    make_plan_left_sidebar.prototype.tagName = "tr";

    make_plan_left_sidebar.prototype.events = {
      "mousedown .name": "dragstart"
    };

    make_plan_left_sidebar.prototype.template = function(model, i) {
      var html;
      html = "<td class=\"no\">" + i + "</td>" + "<td class=\"sex\">" + model.get_sex_text() + "</td>" + "<td class=\"group\">" + model.get("guest_type_value") + "</td>" + "<td class=\"name\" style=\"background-image:url(" + model.get_guest_image() + ");\"></td>" + "<td class=\"tablename\">" + model.get_table_text() + "</td>";
      return _.template(html);
    };

    make_plan_left_sidebar.prototype.render = function() {
      this.$el.html(this.template(this.model, this.model.get("index")));
      this.delegateEvents();
      return this;
    };

    make_plan_left_sidebar.prototype.setMainView = function(view) {
      return this.main_view = view;
    };

    make_plan_left_sidebar.prototype.dragstart = function(e) {
      if (this.model.hasTable()) return;
      this.main_view.onDrag = true;
      this.dragbox = new Re.views.make_plan_drag_box({
        model: this.model
      });
      this.dragevent = $(window).bind("mousemove", this.drag);
      return this.mouseupevent = $(window).bind("mouseup", this.mouseup);
    };

    make_plan_left_sidebar.prototype.drag = function(e) {
      return this.dragbox.near(e);
    };

    make_plan_left_sidebar.prototype.mouseup = function(e) {
      $(window).unbind("mousemove", this.drag);
      $(window).unbind("mouseup", this.mouseup);
      this.dragbox.remove();
      this.main_view.onDrag = false;
      return this.main_view.drop(this);
    };

    make_plan_left_sidebar.prototype.refresh = function(seat_id) {
      var column;
      if (seat_id == null) seat_id = false;
      if (!seat_id) {
        this.model.set("seat_id", false);
        this.model.set("table_id", "0");
        this.model.set("table_name", "");
        this.$(".tablename").html("");
        return;
      }
      this.model.set("seat_id", seat_id);
      column = Re.usertable.get_table(seat_id);
      this.model.set("table_id", column.table_id);
      this.model.set("table_name", column.name);
      return this.$(".tablename").html(this.model.get_table_text());
    };

    return make_plan_left_sidebar;

  })(Backbone.View);

  Re.views.make_plan_drag_box = (function(_super) {

    __extends(make_plan_drag_box, _super);

    function make_plan_drag_box() {
      make_plan_drag_box.__super__.constructor.apply(this, arguments);
    }

    make_plan_drag_box.prototype.tagName = "div";

    make_plan_drag_box.prototype.className = "drag_box";

    make_plan_drag_box.prototype.initialize = function() {
      this.$el.html("<image src=\"" + this.model.get_guest_image() + "\">");
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

  Re.usertable = new Re.models.usertable();

}).call(this);
