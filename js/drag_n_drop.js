// JavaScript Document
	var draggedDiv;

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
			
		//$(".ui-widget-content ui-corner-tr dragfalse").disableSelection();	
		
		$(".droppable").droppable({
			activeClass: "ui-state-highlight",
			over: function (event, ui){
				divid = "#"+this.id;

				edited_Flag=1;
				
			},
			drop: function( event, ui ) {
				deleteImage( ui.draggable, divid );
				
				
			}
			
		});

		function deleteImage( $item ,divid) {
					$item.fadeOut(function() {
					var key = divid.replace("#","");
					
					draggedDivId = "#"+$item.parent("div").attr("id");
					
					edited_Flag=1;
					
					var draggedDivKey = draggedDivId.replace("#","");
					
					
					
					var ul_length = $( "div", $(divid) ).length;
					var oldUl = $( "div", $(divid) );
					
					
					
					
					
					
					
					var parentUlId = $(this).parent('div').attr('id');
					
					
					
					
					
					
					var currentLiId = this.id;
					
					var dragDivLiHtml = $(this).html();
					
					
				
					var currentLiIdArray = currentLiId.split("_");
					
					

					var itemid = currentLiIdArray[1];
					var groupid = divid+"_"+itemid;
					
					
					var liSelector = "#"+parentUlId+" #"+currentLiId;	
					
					
					
					var dragDivUl = draggedDivId;
					
					
					
						
					if(ul_length > 0 && draggedDivId !== "#tst")
					{
						
						var dropDivUl = divid +" div";
						
						
					
						var dragDivLength = divid.length;
						
					
						var drgDvId = draggedDivId.replace("#","");
						
						
						
						
						
						var dropDivHtml = 	$(dropDivUl).html();
						//var text=$(dropDivUl+" div a").html();
						
						var comment1hidden=$(dropDivUl+" div a .comeent1_hidden").val();
						
						var text1="<span  style='font-size:14px'>"+comment1hidden+"</span>";

						
					 
						 
						var newtext=text1;
						
						
					
						
						var drpDivHiddenVal = $(divid+" div div").attr('id');
						
						
						draggedDivId = "#"+$(draggedDivId).parent("div").attr("id");
						draggedDivKey = draggedDivId.replace("#","");
						
						
						
						var liIdArray = drpDivHiddenVal.split("_");	
						

						$(dropDivUl).remove();

						//$(dragDivUl +" li").remove();
						
						
						
						if(draggedDivId !== "#tst" && dropDivHtml !== null )
						{
							
							//alert(dropDivHtml);
							
							$(dragDivUl).append(dropDivHtml);
							
							
							
					 
					 		$(dragDivUl+" div a").attr("title",newtext);
							$(".tooltip").tipTip();	
							
						
							
							var tablediv = $(divid).parent("div").attr("id");
							
							//var tablename=$("#"+tablediv+" p b a  span").html();
							var tabledivtoken = tablediv.split("_");
						        var tablename=$("#table_"+tabledivtoken[1]+" span").html();
							var guest_id_table = groupid.split("_");
							
							
							
						
							$("#tablename_"+guest_id_table[1]).html(tablename.slice(0,2));
						      
						
							var tablediv2 = $(draggedDivId).parent("div").attr("id");
							var tablename2=$("#"+tablediv2+" p b a span").html();
							var guest_id_table2=liIdArray[1];
							
							
						
							
							$("#tablename_"+guest_id_table2).html(tablename2);
							
							
							
							
							$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
							});	
							
							$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':draggedDivId+"_"+liIdArray[1]}, function(data) {
												
							});				
							
						}
						
									
					 }
					else if(ul_length > 0 && draggedDivId === "#tst")
					 {
					 	
					 	$(liSelector).show();
						
						
						
						
						var dropDivUl = divid +" div";
						
						var dropLid = $(dropDivUl+" div").attr("id");
						var dropLidArr = dropLid.split("_");
						var dropItemid = dropLidArr[1];
						
						var tablediv = $(divid).parent("div").attr("id");
						//var tablename=$("#"+tablediv+" p b a span").html();
						var tabledivtoken = tablediv.split("_");
					        var tablename=$("#table_"+tabledivtoken[1]+" span").html();
						var guest_id_table = groupid.split("_");
						
						$("#tablename_"+guest_id_table[1]).html(tablename.slice(0,2));
						
						
						$("#tablename_"+dropItemid).html("");
						
						
												
						$(liSelector).removeClass().addClass('dragfalse');
						
					 	$("#tst #"+dropLid).removeClass().addClass("ui-widget-content");
						
						$("#tst #"+dropLid).draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});
											
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});
						$(dropDivUl).remove();
						
						
					 }
					 else if(ul_length <= 0 && draggedDivId !== "#tst")
					 {
					 	draggedDivId = "#"+$(draggedDivId).parent("div").attr("id");
						draggedDivKey = draggedDivId.replace("#","");
						
						var tablediv = $(divid).parent("div").attr("id");
						//var tablename=$("#"+tablediv+" p b a span").html();
						var tabledivtoken = tablediv.split("_");
					        var tablename=$("#table_"+tabledivtoken[1]+" span").html();
						var guest_id_table = groupid.split("_");
						
						$("#tablename_"+guest_id_table[1]).html(tablename.slice(0,2));
						
						
						
						
						
						$(dragDivUl).remove();
						
								
						$.post('set_session.php', {'divitems': draggedDivKey+"_input",'value':""}, function(data) {
												
						});	
						
											
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});	
					 }
					 
					 
					 else if(draggedDivId === "#tst" && ul_length <= 0)
					 {

						
						var tablediv = $(divid).parent("div").attr("id");
						//var tablename=$("#"+tablediv+" p b a span").html();
						var tabledivtoken = tablediv.split("_");
					        var tablename=$("#table_"+tabledivtoken[1]+" span").html();
						var guest_id_table = groupid.split("_");
						
						$("#tablename_"+guest_id_table[1]).html(tablename.slice(0,2));
						
						
						
						$(liSelector).show();
						
					
											
						$.post('set_session.php', {'divitems': key+"_input",'value':groupid}, function(data) {
												
						});
						
						$(liSelector).removeClass().addClass('dragfalse');

					 }
					 
					 
					 
				
					 
					
					 
					 $("<div id='abc_"+key+"' class='gallery ui-helper-reset ui-helper-clearfix' ><div class='ui-widget-content' id ='" +currentLiId+"'  style='border:0;background-color:#F5F8E5;width:80px; height:30px;'>"+$item.html()+"</div></div>").appendTo(divid);
					 
					 var text=$("#abc_"+key+" div a").html();
					 
					var comment1hidden=$("#abc_"+key+" div a .comeent1_hidden").val();
					
					var text1="<span  style='font-size:14px'>"+comment1hidden+"</span>";					 
						 
					var newtext=text1;
						
					
					 
					 $("#abc_"+key+" div a").attr("title",newtext);
					 
					
					$(".tooltip").tipTip();		
						var div_width = $(divid).width();
						var div_height = $(divid).height();
						$(divid+" div").fadeIn(1);
						$(divid+" div").animate({height:div_height, width:div_width},1);
						$(divid+" div").show();
					
						$(".droppable div div").draggable({				
								cancel: ".dragfalse",
								revert: "invalid", // when not dropped, the item will revert back to its initial position
								helper: "clone",
								cursor: "move",
								opacity: "0.50"
							});	
					
					
					 
						$(".droppable div").dblclick( function () { 
																
							
							
							var thisDivId = $(this).parent().parent().attr("id");
							var thisLiId = this.id;
							
							var itemArray = thisLiId.split("_");
							var item_id = itemArray[1];
								if(typeof(thisDivId) !== 'undefined')
								{
									var thiskey = thisDivId.replace("#","");
									
									
									
									$("#tablename_"+item_id).html("");
									//$("#"+ulId +" #"+ thisLiId+" div").show(); 
									
									var spanhtml = $("#"+thisDivId+ " div div").html();
									
									var spandiv = $("#"+thisDivId+ " div").html();
									
																		
									var position =$("#"+thisDivId+ " div").position();
									var scrollTop = $(window).scrollTop();
									var scrollLeft = $(window).scrollLeft(); 
									var positionDragLeft=position.left-scrollLeft;
									var positionDragTop=position.top-scrollTop;
									
									
									
									
									
									
									var sample_div='<div style="position:fixed; opacity:.5; border:1px solid #A2A78C; background:white;top:'+positionDragTop+'px;left:'+positionDragLeft+'px;" id="copy_div">'+spandiv+'<div>';
								
									var position1 =$("#guests_conatiner").offset();
									
									
									var positionDropLeft=position1.left-scrollLeft+150;
									var positionDropTop=position1.top-scrollTop+200;
									
								
									var position_main =$("#tst div#"+thisLiId).offset();
								
									var scrool_prev=$("#guests_conatiner").scrollTop();
								
									var positionMainDropLeft=position_main.left-scrollLeft;
									var positionMainDropTop=position_main.top-scrollTop+scrool_prev;
								
									
									
									$("#guests_conatiner").scrollTop(positionMainDropTop-position1.top);
									
									var scrool_div=$("#guests_conatiner").scrollTop();
									
									
									
									var positionMainDropTop=positionMainDropTop-scrool_div;
									//$("#guests_conatiner").scrollTop(scrool_new);
								
									
									$("body").append(sample_div);
									
									$('#copy_div').animate({
										
										top: positionDropTop,
										left:positionDropLeft
									  }, 1000, function() {
										
											$('#copy_div').animate({
												
												top: positionMainDropTop,
												left:positionMainDropLeft
											  }, 1000, function() {
												
												
												
												$("#copy_div").remove();
												
												$("#tst div#"+ thisLiId).removeClass().addClass('ui-widget-content');
											});
										
									});
									
									
									
									
									$("#"+thisDivId+ " div").remove();
									
									$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
														
									});	
									
								
									
															
									
																
									
									
									
									$("#tst div#"+ thisLiId).draggable({				
										cancel: ".dragfalse",
										revert: "invalid", // when not dropped, the item will revert back to its initial position
										helper: "clone",
										cursor: "move",
										opacity: "0.50"
									});
								}
						});	
						
						
						
					
				});
				
			
		}
		
	
	
	$(".tooltip").tipTip();	
	
	});
	
	function resetFormItems(){
		
		$(".droppable").each(function() {
			var div_id = "#"+this.id;
			
			var ulLength = $( "div", div_id).length;
			
			if(ulLength > 0)
			{
				
				var liId =  $(div_id+" div div").attr('id');
				
				var liArray = liId.split("_");
				var itemid = liArray[1];
				
				$("#tablename_"+itemid).html("");
				
				
				edited_Flag=1;
				
				var spanhtml = $( "div", div_id).html();				
				
				$("#tst #"+liId).removeClass().addClass('ui-widget-content');
				$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
												
				});	
				
			
		
				
				
				$("#tst #"+liId).draggable({				
					cancel: ".dragfalse",
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					helper: "clone",
					cursor: "move",
					opacity: "0.50"
				});
				
			}
			
		});
		$(".droppable div").remove();
		
		

	}
	
	$(function(){
			   
		$(".droppable div").dblclick( function () { 
							
							var thisDivId = $(this).parent().parent().attr("id");
							
							
							var thisLiId = this.id;
							
							edited_Flag=1;
							
							var itemArray = thisLiId.split("_");
							var item_id = itemArray[1];
							
							if(typeof(thisDivId) !== 'undefined')
								{
									var thiskey = thisDivId.replace("#","");
									
									
									
									$("#tablename_"+item_id).html("");
									
									
									//$("#"+ulId +" #"+ thisLiId+" div").show(); 
									
									var spanhtml = $("#"+thisDivId+ " div div").html();
									
									var spandiv = $("#"+thisDivId+ " div").html();
									
																		
									var position =$("#"+thisDivId+ " div").position();
									var scrollTop = $(window).scrollTop();
									var scrollLeft = $(window).scrollLeft(); 
									var positionDragLeft=position.left-scrollLeft;
									var positionDragTop=position.top-scrollTop;
									
									
									
									
									
									
									var sample_div='<div style="position:fixed; opacity:.5; border:1px solid #A2A78C; background:white;top:'+positionDragTop+'px;left:'+positionDragLeft+'px;" id="copy_div">'+spandiv+'<div>';
									
								
									
									
									
									
									
								
									var position1 =$("#guests_conatiner").offset();
									
									
									var positionDropLeft=position1.left-scrollLeft+150;
									var positionDropTop=position1.top-scrollTop+200;
									
								
									var position_main =$("#tst div#"+thisLiId).offset();
								
									var scrool_prev=$("#guests_conatiner").scrollTop();
								
									var positionMainDropLeft=position_main.left-scrollLeft;
									var positionMainDropTop=position_main.top-scrollTop+scrool_prev;
								
									
									
									$("#guests_conatiner").scrollTop(positionMainDropTop-position1.top);
									
									var scrool_div=$("#guests_conatiner").scrollTop();
									
									
									
									var positionMainDropTop=positionMainDropTop-scrool_div;
									//$("#guests_conatiner").scrollTop(scrool_new);
								
									
									$("body").append(sample_div);
									
									
									$('#copy_div').animate({
										
										top: positionDropTop,
										left:positionDropLeft
									  }, 1000, function() {
										
											$('#copy_div').animate({
												
												top: positionMainDropTop,
												left:positionMainDropLeft
											  }, 1000, function() {
												
												
												
												$("#copy_div").remove();
												
												$("#tst div#"+ thisLiId).removeClass().addClass('ui-widget-content');
											});
										
									});
												
									
									
									$("#"+thisDivId+ " div").remove();
									
									$.post('set_session.php', {'divitems': thiskey+"_input",'value':""}, function(data) {
														
									});	
									//alert($("#tst ul li ul li ul#subul_4").html());
									
									
									
									
									
									$("#tst div#"+thisLiId).draggable({				
										cancel: ".dragfalse",
										revert: "invalid", // when not dropped, the item will revert back to its initial position
										helper: "clone",
										cursor: "move",
										opacity: "0.50"
									});
									
								}
						});
	});
	function goBack()
	{
		
		$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
												
		});	
		javascript:history.go(-1);
	}
