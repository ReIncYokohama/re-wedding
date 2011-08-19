$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog" ).dialog( "destroy" );
		
		var row_number = $( "#row_number" ),
			column_number = $( "#column_number" ),
			seat_number = $("#seat_number"),
			name=$("#name"),
			allFields = $( [] ).add( row_number ).add( column_number ).add( seat_number ).add( name ),
			tips = $( ".validateTips" );
		
		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

				
		function checkLength( o, n, min, max ) {
		
			if( o.val().length > 0  ) {
				if ( o.val().length > max || o.val().length < min ) {
					o.addClass( "ui-state-error" );
					updateTips( "Length of " + n + " must be between " +
						min + " and " + max + "." );
					return false;
				} else {
					return true;
				}
			}else{
				var mess;
				if(n =='seat_number')
				 mess = 'Seat Number Required';
				else if(n=='column_number')
				 mess = 'Table Number Required';
				else if(n=='row_number')
					mess ='Row Number Required';
				else if(n=='name')
					mess ='Plan Name Required';
				else
				  mess =n;
				  
				o.addClass( "ui-state-error" );
				updateTips( mess  );
				return false;
			}	
		
		}
		function checkRegexp( o, regexp, n ) {
		
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#plan_form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 460,
			show: "fade",
			hide: "blind",
			modal: true,
			buttons: {
				"ログイン": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					//alert(email);
					
					bValid = bValid && checkLength( name, "name", 1, 100 );
					bValid = bValid && checkLength( row_number, "row_number", 1, 3 );
					bValid = bValid && checkLength( column_number, "column_number", 1, 3 );
					bValid = bValid && checkLength( seat_number, "seat_number", 1, 3 );
					

					
					
					if ( bValid ) {
						var max_rows = $("#max_rows").val();
						var max_columns = $("#max_columns").val();
						var max_seats = $("#max_seats").val();
						
						if(row_number.val() > max_rows)
						{
							//alert("Maximum Row number allowed for this room is :"+max_rows);
							$("#row_number").addClass( "ui-state-error" );
							updateTips("Maximum Row number allowed for this room is :"+max_rows);
							return false;
						}
						if(column_number.val() > max_columns)
						{
							//alert("Maximum Table number allowed in each Row is :"+max_columns);
							$("#column_number").addClass( "ui-state-error" );
							updateTips("Maximum Table number allowed in each Row is :"+max_columns);
							return false;
						}
						if(seat_number.val() > max_seats)
						{
							//alert("Maximum Seat number allowed in each table is :"+max_seats);
							$("#seat_number").addClass( "ui-state-error" );
							updateTips("Maximum Seat number allowed in each table is :"+max_seats);
							return false;
						}

						$('#plan_new').submit();
						/*$.post('email_check_db.php', {'email': email.val()}, function(data) {
							if( data == 1 ){
								$('#login').submit();
							}else{
								
								$(function() {
									// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
									$( "#dialog" ).dialog( "destroy" );
								
									$( "#dialog-message-login" ).dialog({
										modal: true,
										buttons: {
											Ok: function() {
												$( this).dialog( "close" );
												$( "#login-form" ).dialog( "open" );
											}
										}
									});
								});
								
							}
							});*/
						
						
						
						
						 
						$( this ).dialog( "close" );
					}
				},
				"キャンセル": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#create_plan" )
			.button()
			.click(function() {
				$( "#plan_form" ).dialog( "open" );
			});
	});