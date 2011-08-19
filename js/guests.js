$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		//$( "#dialog" ).dialog( "destroy" );
		$.fx.speeds._default = 400;
		
	
		
			$("#menu_menu").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				//prerendered: true,
				persist: "location"
			});
			
		$( "#menu_dialog" ).dialog({
			autoOpen: false,
			height: 300,
			width: 420,
			//show: "slide",
			hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
						var guest_menu_id = $("#guest_menu_id").val();
						
						var flag = false;
						
						if ( document.guest_form_menu.menu_item_id.length )
					    {
						  for (var x = 0; x < document.guest_form_menu.menu_item_id.length; x++)
						  {
							 if (document.guest_form_menu.menu_item_id[x].checked == true)
							 {
								flag = true;
							 }							 
							 
						  }
					    }
						if(flag == false)
						{
							alert("You must have to select atleast one Gift");	
							return false;
						}
						
						var is_edit = $("#menu_edit").val();

						$.post("ajax/insert_guest_menu.php",{'guest_id':guest_menu_id,'is_edit':is_edit}, function(data){
																						  
						});
						

						if ( document.guest_form_menu.menu_item_id.length )
					    {
						  for (var x = 0; x < document.guest_form_menu.menu_item_id.length; x++)
						  {
							 if (document.guest_form_menu.menu_item_id[x].checked == true)
							 {
								var menuid = document.guest_form_menu.menu_item_id[x].value;
								
								
								
								$.post("ajax/insert_guest_menu.php",{'menuid':menuid,'guest_id':guest_menu_id}, function(data){
																								  
								});
							 }
							 
							 
						  }
					    }
						
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
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		
	
		
		$( "#gift_dialog" ).dialog({
			autoOpen: false,
			height: 300,
			width: 420,
			//show: "slide",
			hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {
						var guest_id = $("#guest_id").val();
						var group_id = $("#group_id").val();
						
						var flag = false;
						
						if(group_id!="")
						{
							if ( document.guest_form.gift_id.length )
							{
							  for (var x = 0; x < document.guest_form.gift_id.length; x++)
							  {
								 if (document.guest_form.gift_id[x].checked == true)
								 {
									flag = true;
								 }							 
								 
							  }
							}
							else if(document.guest_form.gift_id.checked == true)
							{
							flag = true;	
							}
						}
						else
						{
							flag=true;	
						}
						if(flag == false)
						{
							alert("You must have to select atleast one Gift");	
							return false;
						}
						
						var is_edit = $("#gift_edit").val();
						
						if(is_edit==1)
						{
							$.post("ajax/insert_guest_gift.php",{'guest_id':guest_id,'is_edit':is_edit}, function(data){
																							  
							});
						}
						

						if ( document.guest_form.gift_id.length )
					    {
						  for (var x = 0; x < document.guest_form.gift_id.length; x++)
						  {
							 if (document.guest_form.gift_id[x].checked == true)
							 {
								var giftid = document.guest_form.gift_id[x].value;
								

								$.post("ajax/insert_guest_gift.php",{'giftid':giftid,'group_id':group_id,'guest_id':guest_id}, function(data){
								
																								  
								});
							 }
							 
							 
						  }
					    }
						else if(document.guest_form.gift_id.checked == true)
						{
								var giftid = document.guest_form.gift_id.value;
								

								$.post("ajax/insert_guest_gift.php",{'giftid':giftid,'group_id':group_id,'guest_id':guest_id}, function(data){
								
																								  
								});	
							
						}
						
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
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
});
		
function guest_menus(id)
{
checkGuestMenus(id);

$("#guest_menu_id").val(id);
$("#menu_dialog").dialog("open");
}
function guest_gifts(id)
{
	
$.post("ajax/check_guest_gift.php",{'guest_id':id}, function(data){
			
			
			if(data == "0")
			{
					$("#gift_edit").val(0);
					$("#group_id").val(0);
					$("#gift_array").html("None");				
			}
			else
			{
				var menuids = data;
				var menuidArrfull = data.split("|");
				
				var groupid=menuidArrfull['1'];
				var giftstring=menuidArrfull['0'];
				
				$("#gift_edit").val(1);
				
				generateGiftchecked(groupid,giftstring);
				$("#group_id").val(groupid);
				

			}
			
	});

$("#guest_id").val(id);
$("#gift_dialog").dialog("open");
}

function generateGiftchecked(value,giftstring)
{
	
	if(value!="")
	{
	
		
		var giftstringArray=giftstring.split(",");
	    $.post('generate_giftarray.php',{'group_id':value,'giftstringArray':giftstringArray}, function(data) {
	      
			$('#gift_array').html(data);
			
		});
	
	}
	else
	{
		$("#gift_array").html("None");
	}

}


function checkGuestMenus(guestid)
{
	if ( document.guest_form_menu.menu_item_id.length )
					{
					  for (var x = 0; x < document.guest_form_menu.menu_item_id.length; x++)
					  {						 
							document.guest_form_menu.menu_item_id[x].checked = false;						 
						 
					  }
					}
	$.post("ajax/check_guest_menu.php",{'guest_id':guestid}, function(data){
			
			
			if(data == "0")
			{
					
					$("#guest_menu_edit").val(0);
									
			}
			else
			{
				var menuids = data;
				var menuidArr = data.split(",");
				var nummenu = menuidArr.length;
				$("#guest_menu_edit").val(1);
				for (var i=0; i < nummenu; i++)
				{
					if ( document.guest_form_menu.menu_item_id.length )
					{
					  for (var x = 0; x < document.guest_form_menu.menu_item_id.length; x++)
					  {
						 
						 if (document.guest_form_menu.menu_item_id[x].value == menuidArr[i])
						 {
							 document.guest_form_menu.menu_item_id[x].checked = true;							
							
						 }
						
						 
						 
					  }
					}
				}

			}
			
	});
}