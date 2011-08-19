//$(document).ready(function()
	var uncVr;
	var respect;
	var respect_id;
	var sex;
	var description;
	var name;
	var respect_title;
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		//$( "#dialog" ).dialog( "destroy" );
		$.fx.speeds._default = 400;
	
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 250,
			width: 420,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
					 	var ulid = $("#ulId").val();
						var userid = $("#userid").val();
						//alert(ulid);
						name =$("#name").val() ;
						respect = $("#respect_id").val();
						var respectArr = respect.split("_");
						respect_id = respectArr[0];
						respect_title = respectArr[1];
						sex = $("#sex").val();
						
						description = $("#description").val();
						//var insert_item_id = 0;
						//$('#'+ulid+' li:first').before('<li>'+item_name+'</li>'); 						
						$.post('insert_user_item.php', {'name': name,'catul':ulid,'respect_id':respect_id,'sex':sex,'description':description}, function(data) {																												
							checkData(data,ulid);						
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
			height: 250,
			width: 420,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
					 	var ulid = $("#ulId_edit").val();
						//var userid = $("#userid_edit").val();
						var edit_item_id =uncVr; //$("#edit_li_id").val();
						var divid = $("#divid").val();
						
						name =$("#name_edit").val() ;
						respect = $("#respect_id_edit").val();
						var rspArr = respect.split("_");
						respect_id = rspArr[0];
						respect_title = rspArr[1];

						sex = $("#sex_edit").val();
						description = $("#description_edit").val();
						//var insert_item_id = 0;
						//$('#'+ulid+' li:first').before('<li>'+item_name+'</li>'); 
						$.post('edit_user_item.php', {'name': name,'catul':ulid,'respect_id':respect_id,'sex_edit':sex,'description_edit':description,'edit_item_id':edit_item_id,'divid':divid}, 						
						function(data) {
	
							checkEditData(data,ulid,edit_item_id,divid);
							//alert(data)
							
						});	
						
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
	
					$( this ).dialog( "close" );
				},
				閉じる:function() {
			
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});
		
		/*$( "#cat_edit" ).dialog({
			autoOpen: false,
			height: 250,
			width: 420,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {

						var cat_name = $("#cat_names").val();
						var cat_desc = $("#cat_description").val();
						var cat_id = $("#cat_id").val();
						$("#gallery li#cat_"+cat_id+" span strong").html(cat_name);
						
						$.post('ajax/edit_category_name.php',{'cat_name':cat_name,'cat_desc':cat_desc,'cat_id':cat_id}, function (data){
																																  
						});
						/*$.post('edit_user_item.php', {'name': name,'catul':ulid,'respect_id':respect_id,'sex_edit':sex,'description_edit':description,'edit_item_id':edit_item_id,'divid':divid}, 						
						function(data) {
							checkEditData(data,ulid,edit_item_id,divid);
							
						});	*//*
						
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
	
					$( this ).dialog( "close" );
				},
				閉じる:function() {
			
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});*/
		
		
		$( "#table_edit" ).dialog({
			autoOpen: false,
			height: 250,
			width: 420,
			//show: "blind",
			//hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
					 	var tnid = $("#table_name").val();
						var name = $("#table_name :selected").text();
						var id = $("#table_id").val();
						
						$("#table_"+id+ " a").html(name+"<span id = 'tnameid' style= 'display:none'>"+tnid+"</span>");
				
						
						$.post('ajax/edit_table_name.php', {'tnid': tnid,'id':id}, function(data) {
							/*if(parseInt(data) > 0)
							{
								$("#table_"+id+ " a").removeAttr('onclick');
								$("#table_"+id+ " a").click(function(){
									edit_table_name(id,name);								 
								});
							}*/
						});	
						
						$( this ).dialog( "close" );
						
					
				},
				キャンセル: function() {
	
					$( this ).dialog( "close" );
				},
				閉じる:function() {
			
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});
		
		

		
	});
	
	function checkEditData(itemid,ulid,edit_item_id,divid)
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

		var hrefs = "javascript:editItem('"+ulid+"',"+itemid+",'"+name+"','"+respect+"','"+sex+"','"+description+"','"+divid+"');";

		if(divid != 'tst')
		{			
			
			$("#"+divid+ " ul li#item_"+edit_item_id+" span").html(name+"&nbsp;"+respect_title+" &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a>");
			$("#"+divid+ " ul li#item_"+edit_item_id+" span a").attr('href',hrefs);
			$("#"+divid+ " ul li").attr('id','item_'+itemid);
			
			
			/*$("#"+ulid+" li#item_"+edit_item_id).before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon01.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+name+"&nbsp;"+respect_title+" &nbsp;</span></li>");*/
			//alert($("#"+ulid+" li#item_"+edit_item_id+" span").html());
			$("#"+ulid+" li#item_"+edit_item_id+" span").html(name+" "+respect_title+" <a style='display:none;' title='subul_"+ulid+"' ><img src='img/edit.gif' border='0' /></a>");
			
			$("#"+ulid+" li#item_"+edit_item_id+" span a").attr('href',hrefs);

				//$("#"+ulid+" li#item_"+edit_item_id).remove();

			
			
			checkFont(divid);
		}
		
		else
		{
			
			if(itemid != edit_item_id)
			{
	
				
				$("#"+ulid+" li#item_"+edit_item_id).before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+name+"&nbsp;"+respect_title+" &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a></span></li>");
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
				$("#"+ulid+" li#item_"+edit_item_id+" span").html(name+"&nbsp;"+respect_title+" &nbsp;<a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a>");
				//$("#"+ulid+ " ul li#item_"+edit_item_id+" span a").attr('href',hrefs);
				$("#"+ulid+" li#item_"+edit_item_id+" a").attr('href',hrefs);
			}
			
			
				
		}
		
	}
	
	
	function checkData(itemid,ulid)
	{
		
		var hrefs = "javascript:editItem('"+ulid+"',"+itemid+",'"+name+"','"+respect+"','"+sex+"','"+description+"','tst');";		
		$("#"+ulid+" li:first").before("<li class = 'ui-widget-content' id = 'item_"+itemid+"' title ='"+ulid+"' > <div id='icon_"+itemid+"'  style='width:20px;float:left;'><img src='img/icon02.jpg' border='0' id='icon_"+itemid+"'/></div><span>"+name+" &nbsp;"+ respect_title +" &nbsp; <a title='"+ulid+"'><img border='0' src='img/edit.gif' style='float: right; width: 15px; height: 10px; vertical-align: top;'></a></span></li>");
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
		$("#ulId").val(ulid);
		$("#userid").val(userid);
		$( "#dialog-form" ).dialog( "open");
	}
	
	
	function editItem(ulid, itemid,itemname,respect,sex, description,divid)
	{
		
		$("#ulId_edit").val(ulid);

		$("#edit_li_id").val(itemid);
		uncVr=itemid;
		$("#divid").val(divid);
		
		$("#name_edit").val(itemname);
		$("#respect_id_edit").val(respect);
		$("#sex_edit").val(sex);
		$("#description_edit").val(description);
		$( "#dialog-form_edit" ).dialog( "open");	
	}

function edit_guest_cat(catid,name)
{

	$("#cat_id").val(catid);
	$("#cat_names").val(name);
	$( "#cat_edit" ).dialog( "open");
	//$("#gallery li#cat_"+catid+" span strong").html("Ahad"+catid);
}

function edit_table_name(id)
{
	
	$("#table_id").val(id);
	var newname_id = $("#table_"+id+ " a span").html();
	if(newname_id > 0)
	{
		$("#table_name").val(newname_id);	
	}
	$("#table_edit").dialog("open");	

}