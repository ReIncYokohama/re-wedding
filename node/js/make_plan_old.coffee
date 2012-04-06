Re ?= {}
Re.models ?= {}

class Re.models.usertable
  _data:""
  load:(json)->
    @_data = json
    console.log json

Re.usertable = new Re.models.usertable()