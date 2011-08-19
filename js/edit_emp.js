// JavaScript Document
function edit_employee(emp_id) {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		
		$( "#dialog" ).dialog( "destroy" );
		
		var name = $( "#name_"+emp_id ),
			email = $( "#email_"+emp_id ),
			password = $( "#password_"+emp_id ),
			conf_password = $( "#conf_password_"+emp_id ),
			allFields = $( [] ).add( name ).add( email ).add( password ).add( conf_password ),
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
			//alert(o.val());
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
				o.addClass( "ui-state-error" );
				updateTips( n + " cann't be empty." );
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
		function checkpassword_confirm( o, p, n ){
			
			if( o.val() == p.val() ) {
				return true;
			} else {
				p.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			}
		}
		
		$( "#dialog-edit-form_"+emp_id ).dialog({
			autoOpen: false,
			height: 500,
			width: 350,
			show: "fade",
			hide: "blind",
			modal: true,
			buttons: {
				"Edit account": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( name, "Username", 3, 16 );
					bValid = bValid && checkLength( email, "Email", 6, 80 );
					bValid = bValid && checkLength( password, "Password", 5, 16 );
					bValid = bValid && checkLength( conf_password, "Confirm password", 5, 16 );

					//bValid = bValid && checkRegexp( name, /^([0-9_])+$/i, "Username may consist of 0-9, underscores, begin with a letter." );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
					bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
					bValid = bValid && checkpassword_confirm( password, conf_password, "Confirm password Does not match" );
					
					if ( bValid ) {
					$('#edit_emp_'+emp_id).submit();
						
						
						
						 
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		
				$( "#dialog-edit-form_"+emp_id ).dialog( "open" );

			
	}