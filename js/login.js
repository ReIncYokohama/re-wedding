$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog" ).dialog( "destroy" );
		
		var email = $( "#email" ),
			password = $( "#password" ),
			allFields = $( [] ).add( email ).add( password ),
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
				if(n =='Email')
				 mess = 'メールアドレスが未入力です';
				else if(n=='Password')
				 mess = 'パスワードが未入力です。';
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
		
		$( "#login-form" ).dialog({
			autoOpen: false,
			height: 250,
			width: 300,
			show: "fade",
			hide: "blind",
			modal: true,
			buttons: {
				"ログイン": function() {

					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					//alert(email);
					
					bValid = bValid && checkLength( email, "Email", 5, 80 );
					bValid = bValid && checkLength( password, "Password", 5, 16 );
					

					
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
	/*				bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );*/
					//bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
					
					
					if ( bValid ) {
						/*$( "#users tbody" ).append( "<tr>" +
							"<td>" + name.val() + "</td>" + 
							"<td>" + email.val() + "</td>" + 
							"<td>" + password.val() + "</td>" +
						"</tr>" );*/
						$('#login').submit();
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

		$( "#login-user" )
			.button()
			.click(function() {
				$( "#login-form" ).dialog( "open" );
			});
	});
