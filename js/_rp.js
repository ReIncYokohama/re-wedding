//$(document).ready(function(){
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		//$( "#dialog" ).dialog( "destroy" );
		$.fx.speeds._default = 400;

		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 210,
			width: 380,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
					 	var ulid = $("#ulId").val();
						var userid = $("#userid").val();
						//alert(ulid);
						var item_name =$("#item_name").val() ;
						var designation = $("#designation").val();
						var freetext = $("#freetext").val();
						//var insert_item_id = 0;
						//$('#'+ulid+' li:first').before('<li>'+item_name+'</li>'); 
						$.post('insert_user_item.php', {'itemname': item_name,'catul':ulid,'designation':designation,'freetext':freetext}, function(data) {
							checkData(data,ulid,item_name);						
						});	
						
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
						//var day = document.getElementById("day").value;
						//var month = document.getElementById("mm").value;
						//var year = document.getElementById("year").value;
						//var daycount = document.getElementById("daycount").value;
					//cancelButton(day,month,year,daycount);
					$( this ).dialog( "close" );
				},
				閉じる:function() {
					//var day = document.getElementById("day").value;
					//var month = document.getElementById("mm").value;
					//var year = document.getElementById("year").value;
					//closeButton(day,month,year);				
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		
		
		
		$( "#dialog-form_edit" ).dialog({
			autoOpen: false,
			height: 210,
			width: 380,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
					 	var ulid = $("#ulId_edit").val();
						//var userid = $("#userid_edit").val();
						var edit_item_id = $("#edit_li_id").val();
						//alert(userid);
						var divid = $("#divid").val();
						
						var item_name =$("#item_name_edit").val() ;
						var designation = $("#designation_edit").val();
						var freetext = $("#freetext_edit").val();
						//var insert_item_id = 0;
						//$('#'+ulid+' li:first').before('<li>'+item_name+'</li>'); 
						$.post('edit_user_item.php', {'itemname': item_name,'catul':ulid,'designation':designation,'freetext':freetext,'edit_item_id':edit_item_id}, 						
						function(data) {
	
							checkEditData(data,ulid,edit_item_id,item_name,divid);
							//alert(data)
							
						});	
						
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
						//var day = document.getElementById("day").value;
						//var month = document.getElementById("mm").value;
						//var year = document.getElementById("year").value;
						//var daycount = document.getElementById("daycount").value;
					//cancelButton(day,month,year,daycount);
					$( this ).dialog( "close" );
				},
				閉じる:function() {
					//var day = document.getElementById("day").value;
					//var month = document.getElementById("mm").value;
					//var year = document.getElementById("year").value;
					//closeButton(day,month,year);				
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
	});
	
	function checkEditData(itemid,ulid,edit_item_id,item_name,divid)
	{
		//alert(itemid+','+ulid+','+item_name);
		//$('#'+ulid+' li:first').before("<li>"+item_name+"</li>");
		/*alert("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+item_name+"</span></li>");*/
		/*$("#"+ulid+" li:first").before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+item_name+"</span></li>");
		$("#"+ulid+" li:first").draggable({			
			 // clicking an icon won't initiate dragging
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
			
		
			});	
		*/
		var hrefs = "javascript:editItem('"+ulid+"',"+itemid+",'"+item_name+"','"+divid+"');";
		if(divid != 'tst')
		{			
			
			$("#"+divid+ " ul li#item_"+edit_item_id+" span").html(item_name+"&nbsp; &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a>");
			$("#"+divid+ " ul li#item_"+edit_item_id+" span a").attr('href',hrefs);
			$("#"+divid+ " ul li").attr('id','item_'+itemid);
			
			
			$("#"+ulid+" li#item_"+edit_item_id).before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon01.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+item_name+"&nbsp; &nbsp;</span></li>");
			
			$("#"+ulid+" li#item_"+edit_item_id).remove();
			
			
			checkFont(divid);
		}
		
		else
		{
			
			if(itemid != edit_item_id)
			{
	
				
				$("#"+ulid+" li#item_"+edit_item_id).before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+item_name+"&nbsp; &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a></span></li>");
				$("#"+ulid+ " ul li#item_"+itemid+" span a").attr('href',hrefs);
				
				$("#"+ulid+" li#item_"+itemid).draggable({			
					 // clicking an icon won't initiate dragging
					 cancel:'.dragfalse',
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
					helper: "clone",
					cursor: "move"			
				
					});	
				$("#"+ulid+" li#item_"+edit_item_id).remove();
			}
			else
			{
				$("#"+ulid+" li#item_"+edit_item_id+" span").html(item_name+"&nbsp; &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a>");
				//$("#"+ulid+ " ul li#item_"+edit_item_id+" span a").attr('href',hrefs);
				$("#"+ulid+" li#item_"+edit_item_id+" a").attr('href',hrefs);
			}
			
			
				
		}
		
	}
	
	
	function checkData(itemid,ulid,item_name)
	{
		
		var hrefs = "javascript:editItem('"+ulid+"',"+itemid+",'"+item_name+"','tst');";
		
		$("#"+ulid+" li:first").before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+item_name+" &nbsp; &nbsp; <a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a></span></li>");
		$("#"+ulid+" li:first span a").attr('href',hrefs);
		$("#"+ulid+" li:first").draggable({			
			 // clicking an icon won't initiate dragging
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move"
			
		
			});	
		
	}
	
	function addNewItem(ulid, userid)
	{
		alert("as");
		/*$("#ulId").val(ulid);
		$("#userid").val(userid);
		$( "#dialog-form" ).dialog( "open");	*/
	}
	
	
	function editItem(ulid, itemid,itemname,divid)
	{
		
		$("#ulId_edit").val(ulid);
		//$("#userid_edit").val(userid);
		$("#edit_li_id").val(itemid);
		$("#divid").val(divid);
		
		$("#item_name_edit").val(itemname);
		$( "#dialog-form_edit" ).dialog( "open");	
	}

//});