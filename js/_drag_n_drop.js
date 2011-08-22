/*	var draggedDiv;
	$(function() {
			$("#gallery").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				persist: "location"
			});
		})
	
	
	
	
	$(function() {		
		var $gallery = $( "#gallery" ),
			$trash = $( "#trash" );
		var divid = "";
			
		
		$( ".ui-widget-content").draggable({			
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
			
		
			});			

		
		$(".droppable").droppable({
			activeClass: "ui-state-highlight",
			over: function (event, ui){
				divid = "#"+this.id;
			
				
			},
			drop: function( event, ui ) {
				deleteImage( ui.draggable, divid );
			}
		});

		function deleteImage( $item ,divid) {
				$item.fadeOut(function() {
					var key = divid.replace("#","");
					
					
					draggedDivId = "#"+$item.closest("div").attr("id");
					var draggedDivKey = draggedDivId.replace("#","");
					
					var ul_length = $( "ul", $(divid) ).length;
					var oldUl = $( "ul", $(divid) );
					
					var parentUlId = $(this).closest('ul').attr('id');
					
					var title = this.title;
					var currentLiId = this.id;
					var dragDivLiHtml = $(this).html();
				
					var currentLiIdArray = currentLiId.split("_");

					var itemid = currentLiIdArray[1];
					var groupid = divid+"_"+itemid;

					var liSelector = "#"+parentUlId+" #"+currentLiId;	
					var dragDivUl = draggedDivId + " ul";
					$item.find("img#icon"+itemid).remove();
							
						
					if(ul_length > 0 && draggedDivId !== "#tst")
					{
						var dropDivUl = divid +" ul";	
											
					
						var dropDivHtml = 	$(dropDivUl).html();
						var drpDivHiddenVal = $(divid+" li").attr('id');
						var liIdArray = drpDivHiddenVal.split("_");	
									
						$(dropDivUl).remove();
						//var dragDivHtml = $(dragDivUl).html();
						$(dragDivUl +" li").remove();
						if(draggedDivId !== "#tst" && dropDivHtml !== null )
						{

							$(dragDivUl).append(dropDivHtml);	

							$(divid+" input").val(groupid);	
							$(draggedDivId+" input").val(draggedDivId+"_"+liIdArray[1]);						
							
							$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
							});	
							
							$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':draggedDivId+"_"+liIdArray[1]}, function(data) {
												
							});				
							
						}
						
									
					 }
					 else if(ul_length > 0 && draggedDivId === "#tst")
					 {
					 	var dropDivUl = divid +" ul";
						$(dropDivUl).remove();
					 }
					 else if(ul_length <= 0 && draggedDivId !== "#tst")
					 {
					 	$(dragDivUl).remove();
						
						$(draggedDivId+" input").val('');						
						$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':""}, function(data) {
												
						});	
						
						$(divid+" input").val(groupid);						
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});	
					 }
					 
					 
					 if(draggedDivId === "#tst" )
					 {
					 	$(liSelector).show();
						
						//$(draggedDivId+" #gallery "+liSelector).prepend("<div id = 'icon_"+itemid+"'><img src='img/icon01.jpg' border='0' id='icon"+itemid+"'/></div>");
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid).append("<img src='../img/icon01.jpg' border='0' id='icon"+itemid+"'/>");
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid+" img").fadeIn(400);
						$(divid+" input").val(groupid);
						
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});
						
						$(liSelector).removeClass().addClass('dragfalse');

					 }
					 
					 $("<ul class='gallery ui-helper-reset ui-helper-clearfix'><li class='ui-widget-content' id ='" +currentLiId+"' title='"+title+"'>"+$item.html()+"</li></ul>").appendTo(divid);
						//$(divid+" ul li div").show();
						//$item.find( "img" ).animate({ height: "40", width:"120" });
					var div_width = $(divid).width();
					var div_height = $(divid).height();
					var img_height = div_height-15;
					
					$(divid+" ul li").fadeIn(1);
					$(divid+" ul li").animate({height:div_height, width:div_width},1);
					$(divid+" ul li div").show();
					$(divid+" ul li div img").animate({height:img_height},1);
					$(divid+" ul li div img").animate({width:div_width},1);
					$(".droppable ul li").draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});	

					 
						$(".droppable ul li").dblclick( function () { 
							var ulId = this.title;
							var thisDivId = $(this).parent().parent().attr("id");
							var thisLiId = this.id;
							var itemArray = thisLiId.split("_");
							var item_id = itemArray[1];
							var thiskey = thisDivId.replace("#","");
							//$("#"+ulId +" #"+ thisLiId+" div").show(); 
							
							
							$("#"+thisDivId+ " ul").remove();
							$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
												
							});	
							$("#"+ulId +" #"+ thisLiId).removeClass().addClass('ui-widget-content');
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id+" img").remove();
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id).append("<img src='../img/icon02.jpg' border='0' id='icon"+itemid+"'/>");
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id+" img").slideUp(200).fadeIn(200);
							
							$("#"+ulId +" #"+ thisLiId).draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});
						});	
						$(divid).find("div#icon_"+itemid).remove();
						
						checkFont(divid);
					
				});
				
			
		}

		
	});
	
		$(function(){
		$(".droppable ul li").dblclick( function () { 
				var ulId = this.title;
				
				var thisDivId = $(this).parent().parent().attr("id");
				var thisLiId = this.id;
				var itemArray = thisLiId.split("_");
				var item_id = itemArray[1];
				var thiskey = thisDivId.replace("#","");
				//$("#"+ulId +" #"+ thisLiId+" div").show(); 
				
				
				$("#"+thisDivId+ " ul").remove();
				$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
									
				});	
				
				$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id+" img").attr("src","../img/icon02.jpg");
				$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id+" img").slideUp(200).fadeIn(200);
				$("#"+ulId+ " li#"+thisLiId).attr("class","ui-widget-content");
				$("#"+ulId+ " li#"+thisLiId).draggable({				
					cancel: ".dragfalse",
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					helper: "clone",
					cursor: "move",
					opacity: "0.50"
				});
				//$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id).append("asdfsfds");
			});
	});
	
	function resetFormItems(){
		
		$(".droppable").each(function() {
			var div_id = "#"+this.id;
			var ulLength = $( "ul", div_id).length;
			if(ulLength > 0)
			{
				var ulID = $(div_id+" ul li").attr('title');
				var liId =  $(div_id+" ul li").attr('id');
				var liArray = liId.split("_");
				var itemid = liArray[1];
				$("#"+ulID +" #"+ liId).removeClass().addClass('ui-widget-content');
				$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
												
				});	
				$("#tst ul li ul #"+liId+" #icon_"+itemid+" img").remove();
				$("#tst ul li ul #"+liId+" #icon_"+itemid).append("<img src='../img/icon02.jpg' border='0' id='icon"+itemid+"'/>");
				$("#tst ul li ul #"+liId+" #icon_"+itemid+" img").slideUp(200).fadeIn(200);
			}
			
		});
		$(".droppable ul").remove();

	}
*/

	var draggedDiv;
	$(function() {
			$("#gallery").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				//prerendered: true,
				persist: "location"
			});
		})
	
	
	
	
	$(function() {	

		$(".droppable").each(function (){
			checkFont(this.id);
		});
		
		var $gallery = $( "#gallery" ),
			$trash = $( "#trash" );
		var divid = "";
			
		
		$( ".ui-widget-content").draggable({			
			 // clicking an icon won't initiate dragging
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
			
		
			});			
			
		//$(".ui-widget-content ui-corner-tr dragfalse").disableSelection();	
		
		$(".droppable").droppable({
			activeClass: "ui-state-highlight",
			over: function (event, ui){
				divid = "#"+this.id;
			
				
			},
			drop: function( event, ui ) {
				deleteImage( ui.draggable, divid );
			}
		});

		function deleteImage( $item ,divid) {
				$item.fadeOut(function() {
					var key = divid.replace("#","");
					
					
					draggedDivId = "#"+$item.closest("div").attr("id");
					var draggedDivKey = draggedDivId.replace("#","");
					
					var ul_length = $( "ul", $(divid) ).length;
					var oldUl = $( "ul", $(divid) );
					
					var parentUlId = $(this).closest('ul').attr('id');
					
					var title = this.title;
					var currentLiId = this.id;
					var dragDivLiHtml = $(this).html();
				
					var currentLiIdArray = currentLiId.split("_");

					var itemid = currentLiIdArray[1];
					var groupid = divid+"_"+itemid;

					var liSelector = "#"+parentUlId+" #"+currentLiId;	
					var dragDivUl = draggedDivId + " ul";
					$item.find("img#icon"+itemid).remove();
					
					
						
					if(ul_length > 0 && draggedDivId !== "#tst")
					{
						
						var dropDivUl = divid +" ul";
						var dropDivHref = $(dropDivUl+" li a").attr("href");
						
						var dragDivLength = draggedDivId.length;
						var newlength = dropDivHref.length-(dragDivLength+3);
						var drgDvId = draggedDivId.replace("#","");
						var newHref = dropDivHref.substring(0,newlength);
							newHref = newHref+"'"+drgDvId+"');";
						$(dropDivUl+" li a").attr("href",newHref);
						
						
						var dropDivHtml = 	$(dropDivUl).html();
						var drpDivHiddenVal = $(divid+" li").attr('id');
						var liIdArray = drpDivHiddenVal.split("_");	

						$(dropDivUl).remove();

						$(dragDivUl +" li").remove();
						if(draggedDivId !== "#tst" && dropDivHtml !== null )
						{
							
							//alert(dropDivHtml);
							$(dragDivUl).append(dropDivHtml);							
							$(divid+" input").val(groupid);	
							$(draggedDivId+" input").val(draggedDivId+"_"+liIdArray[1]);				
							
							
							$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
							});	
							
							$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':draggedDivId+"_"+liIdArray[1]}, function(data) {
												
							});				
							
						}
						
									
					 }
					else if(ul_length > 0 && draggedDivId === "#tst")
					 {
					 	///////////Manage Top menu/////////////
					 	$(liSelector).show();
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid+" img").remove();
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid).append("<img src='img/icon01.jpg' border='0' id='icon"+itemid+"'/>");
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid+" img").fadeIn(400);						
						$(liSelector).removeClass().addClass('dragfalse');
						//////////////Manage Drop Div /////////
					 	var dropDivUl = divid +" ul";
						var dropLid = $(divid+" ul li").attr("id");
						var dropLidArr = dropLid.split("_");
						var dropItemid = dropLidArr[1];
						
						$("#tst ul li ul #"+dropLid+" #icon_"+dropItemid+" img").remove();
						$("#tst ul li ul #"+dropLid+" #icon_"+dropItemid).append("<img src='img/icon02.jpg' border='0' id='icon"+itemid+"'/>");
						$("#tst ul li ul #"+dropLid+" #icon_"+dropItemid+" img").fadeIn(400);
						$("#tst ul li ul #"+dropLid).removeClass().addClass("ui-widget-content");
						$(divid+" input").val(groupid);						
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});
						
						$(dropDivUl).remove();
					 }
					 else if(ul_length <= 0 && draggedDivId !== "#tst")
					 {
					 	$(dragDivUl).remove();
						
						$(draggedDivId+" input").val('');						
						$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':""}, function(data) {
												
						});	
						
						$(divid+" input").val(groupid);						
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});	
					 }
					 
					 
					 else if(draggedDivId === "#tst" && ul_length <= 0)
					 {
					 	$(liSelector).show();
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid).append("<img src='img/icon01.jpg' border='0' id='icon"+itemid+"'/>");
						$("#tst ul li ul #"+currentLiId+" #icon_"+itemid+" img").fadeIn(400);
						$(divid+" input").val(groupid);						
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});
						
						$(liSelector).removeClass().addClass('dragfalse');

					 }
					 
					 $(".droppable ul li").css("width",$(divid).width());
					 
					 $("<ul class='gallery ui-helper-reset ui-helper-clearfix'><li class='ui-widget-content' id ='" +currentLiId+"' title='"+title+"'>"+$item.html()+"</li></ul>").appendTo(divid);
						//$(divid+" ul li div").show();
						//$item.find( "img" ).animate({ height: "40", width:"120" });	
						var div_width = $(divid).width();
					$(divid+" ul li").fadeIn(1);
					$(divid+" ul li").animate({height:"50", width:div_width},1);
					$(divid+" ul li div").show();
					$(divid+" ul li div img").animate({height:"35"},1);
					$(divid+" ul li div img").animate({width:div_width},1);
					$(divid+" ul li a img").css("width","20px");
					$(".droppable ul li").draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});	
					
					checkFont(divid);
					 
						$(".droppable ul li").dblclick( function () { 
																
							
							var ulId = this.title;
							var thisDivId = $(this).parent().parent().attr("id");
							var thisLiId = this.id;
							var itemArray = thisLiId.split("_");
							var item_id = itemArray[1];
							var thiskey = thisDivId.replace("#","");
							//$("#"+ulId +" #"+ thisLiId+" div").show(); 
							
							
							$("#"+thisDivId+ " ul").remove();
							$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
												
							});	
							$("#"+ulId +" #"+ thisLiId).removeClass().addClass('ui-widget-content');
							
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id+" img").remove();
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id).append("<img src='img/icon02.jpg' border='0' id='icon"+itemid+"'/>");
							$("#tst ul li ul #"+thisLiId+" #icon_"+item_id+" img").slideUp(200).fadeIn(200);
							
							$("#"+ulId +" #"+ thisLiId).draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});
						});	
						$(divid).find("div#icon_"+itemid).remove();
						
						var itemHref = $($item).find("a").attr('href');						
						var divLength = divid.length;
										
						var newDivlength = itemHref.length-(divLength+3);
						var DvId = divid.replace("#","");
						var newItemHref = itemHref.substring(0,newDivlength);
							newItemHref = newItemHref+"'"+DvId+"');";
						
						$(divid+" ul li a").attr('href',newItemHref);

						//checkHref(ul_length,draggedDivId,divid);
						
						/*var divLength = divid.length;
						var DivHref = $(divid+" ul li a").attr("href");
						var newHrefLength = DivHref.length-(divLength+3);
						var drpDvId = divid.replace("#","");
						var newDpHref = drpDvId.substring(0,newHrefLength);
							newDpHref = newDpHref+drpDvId+"');";
						$(divid+" ul li a").attr("href",newHref);*/
						//var $(divid +" ul li a").attr('href'));
						
					
				});
				
			
		}

		
	});
	
	function resetFormItems(){
		
		$(".droppable").each(function() {
			var div_id = "#"+this.id;
			var ulLength = $( "ul", div_id).length;
			if(ulLength > 0)
			{
				var ulID = $(div_id+" ul li").attr('title');
				var liId =  $(div_id+" ul li").attr('id');
				var liArray = liId.split("_");
				var itemid = liArray[1];
				$("#"+ulID +" #"+ liId).removeClass().addClass('ui-widget-content');
				$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
												
				});	
				$("#tst ul li ul #"+liId+" #icon_"+itemid+" img").remove();
				$("#tst ul li ul #"+liId+" #icon_"+itemid).append("<img src='img/icon02.jpg' border='0' id='icon"+itemid+"'/>");
				$("#tst ul li ul #"+liId+" #icon_"+itemid+" img").slideUp(200).fadeIn(200);
			}
			
		});
		$(".droppable ul").remove();

	}
	$(function(){
			   
		$(".droppable ul li").dblclick( function () { 
							var ulId = this.title;
							var thisDivId = $(this).parent().parent().attr("id");
							var thisLiId = this.id;
							var itemArray = thisLiId.split("_");
							var item_id = itemArray[1];
							var thiskey = thisDivId.replace("#","");
							//$("#"+ulId +" #"+ thisLiId+" div").show(); 
							
							var spanhtml = $("#"+thisDivId+ " ul li span").html();
							$("#"+thisDivId+ " ul").remove();
							$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
												
							});	
							//alert($("#tst ul li ul li ul#subul_4").html());
							
							$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id+" img").attr("src","img/icon02.jpg");
							$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id+" img").slideUp(200).fadeIn(200);
							
							$("#"+ulId+ " li#"+thisLiId+" span").html(spanhtml);
							$("#"+ulId+ " li#"+thisLiId+" span a img").slideUp(200).fadeIn(200);
							$("#"+ulId+ " li#"+thisLiId).attr("class","ui-widget-content");
							$("#"+ulId+ " li#"+thisLiId).draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});
							
							var itemHref = $("#"+ulId+ " li#"+thisLiId+" span a").attr('href');						
							var divLength = thisDivId.length;
										
							var newDivlength = itemHref.length-(divLength+3);
							var newItemHref = itemHref.substring(0,newDivlength);
							
							newItemHref = newItemHref+"tst');";						
							$("#"+ulId+ " li#"+thisLiId+" span a").attr('href',newItemHref);
							//$("#"+ulId+ " li#"+thisLiId+" #icon_"+item_id).append("asdfsfds");*/
						});
	});
	function goBack()
	{
		
		$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
												
		});	
		javascript:history.go(-1);
	}




	function checkFont(divid)
	{
		var isHash = divid.indexOf("#");
		if(isHash != 0)
		{
			divid= "#"+divid;	
		}
		
		var divwidth = parseInt($(divid).width());
			var font_size = parseInt($(divid+" ul li span").css("font-size"));
			var span_width = parseInt($(divid+" ul li span").width());
			
			//alert(span_width+"div"+divwidth+"font"+font_size);
			//$(divid+" ul li").css({'display':'block','width':divwidth});
			
			if(span_width > divwidth)
			{
				
				for(var i = 1; i < font_size; i++)
				{
					
					//$(divid+" ul li span").css("font-size",font_size);
					//span_width = $(divid+" ul li span").width();
					
					font_size = font_size-1;
					$(divid+" ul li span").css("font-size",font_size);
					var newwidth = parseInt($(divid+" ul li span").width());
					if(newwidth <= divwidth)
					{
						break;
					}
					
				}
			}
			
			
			$(divid+" ul li").css("width",divwidth);
			$(divid+" ul li div img").css("width",divwidth);
			$(divid+" ul li a img").css("float","right");
			$(divid+" ul li a img").css("width","15px");
			$(divid+" ul li a img").css("height","10px");
			$(divid+" ul li a img").css("vertical-align","top");
			$(divid+" ul li span").css("whitespace", "no-wrap");
		
	}
	
	/*function checkHref(ul_length,draggedDivId,divid)
	{
		var dragItemId = $(draggedDivId+"ul li").attr('id');
		var dropItemId = $(divid+"ul li").attr('id');
		var draggedSubCatId = $(draggedDivId+" ul li").attr('title');
		var droppedSubCatId = $(dropItemId+" ul li").attr('title');
		
		var dragitemArray = dragItemId.split("_");
		var dragitemid = dragitemArray[1];
		
		var dropitemArray = dragItemId.split("_");
		var dropitemid = dragitemArray[1];
		
		var draggedText = $(draggedDivId+"ul li span").text();
		var droppedText = $(divid+"ul li span").text();
	}*/
	
