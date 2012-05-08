$ = $j;
$(window).load(function() {
$(".target_category").each(function(){
    target_category_el = this;
    previout_status = $(this).next()[0];
    after_status = $(previout_status).next()[0];
    arr = [this,previout_status,after_status];
    arr_children = [];
    for(i=0;i<arr.length;++i){
      arr_children.push($(arr[i]).children());
    }
    
    for(i=0;i<arr_children[0].length;++i){
      height = 0;
      for(j=0;j<arr.length;++j){
        this_height = $(arr_children[j][i]).height();
        if(height<this_height){
          height = this_height;
        }
      }
      for(j=0;j<arr.length;++j){
        $(arr_children[j][i]).height(height);
      }
    }
}
);

});
