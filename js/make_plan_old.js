(function() {

  if (typeof Re === "undefined" || Re === null) Re = {};

  if (Re.models == null) Re.models = {};

  Re.models.usertable = (function() {

    function usertable() {}

    usertable.prototype._data = "";

    usertable.prototype.load = function(json) {
      this._data = json;
      return console.log(json);
    };

    return usertable;

  })();

  Re.usertable = new Re.models.usertable();

}).call(this);
