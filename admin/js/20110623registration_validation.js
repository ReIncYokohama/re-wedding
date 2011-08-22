function validForm(){

   // get all the inputs into an array.
    //var elem   = document.getElementById('registerfrm').elements;
	var str="";
	var email = document.getElementById('mail').value;
	
	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	if(document.getElementById("man_firstname").value=='')
	{
		alert("新郎の姓を正しく入力してください");
		document.getElementById('man_firstname').focus();
		return false;
	}

   if(document.getElementById("man_lastname").value=='')
	{
		alert("「新郎の名を正しく入力してください」");
		document.getElementById('man_lastname').focus();
		return false;
	}
	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の姓のフリガナを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}
	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の名のフリガナを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}

	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}

    if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の姓のフリガナを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の名のフリガナを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

    if(document.getElementById("contact_name").value=='')
	{
		alert("Contact name must not be empty");
		document.getElementById('contact_name').focus();
		return false;
	}
	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}
	if(document.getElementById("marriage_day_with_time").value=='')
	{
		alert("挙式日と時間を入力してください。");
		document.getElementById('marriage_day_with_time').focus();
		return false;
	}
	if(document.getElementById("room_id").value=='')
	{
		alert("必須項目は必ず入力してください。");
		document.getElementById('room_id').focus();
		return false;
	}
	
	if(document.getElementById("religion").value=='')
	{
		alert("挙式種類を選択してください");
		document.getElementById('religion').focus();
		return false;
	}
	
	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}
	if(document.getElementById("party_day_with_time").value=='')
	{
		alert("Party day with time must not be empty");
		document.getElementById('party_day_with_time').focus();
		return false;
	}
	if(document.getElementById("party_room_id").value=='')
	{
		alert("Party room must not be empty");
		document.getElementById('party_room_id').focus();
		return false;
	}
	if(document.getElementById("status").value=='')
	{
		alert("Status must not be empty");
		document.getElementById('status').focus();
		return false;
	}
	if(document.getElementById("zip1").value=='')
	{
		alert("zip1 must not be empty");
		document.getElementById('zip1').focus();
		return false;
	}
	if(document.getElementById("zip2").value=='')
	{
		alert("zip2 must not be empty");
		document.getElementById('zip2').focus();
		return false;
	}
	if(document.getElementById("state").value=='')
	{
		alert("state must not be empty");
		document.getElementById('state').focus();
		return false;
	}
	if(document.getElementById("city").value=='')
	{
		alert("city must not be empty");
		document.getElementById('city').focus();
		return false;
	}
	
	if(document.getElementById("street").value=='')
	{
		alert("street must not be empty");
		document.getElementById('street').focus();
		return false;
	}
	if(document.getElementById("tel").value=='')
	{
		alert("電話が未入力です");
		document.getElementById('tel').focus();
		return false;
	}
	if(document.getElementById("mail").value=='')
	{
		alert("メールアドレスが未入力です");
		document.getElementById('mail').focus();
		return false;
	}
	if(document.getElementById("conemail").value=='')
	{
		alert("conemail  must not be empty");
		document.getElementById('conemail').focus();
		return false;
	}
	if(document.getElementById("user_id").value=='')
	{
		alert("User Id  must not be empty");
		document.getElementById('user_id').focus();
		return false;
	}
	if(document.getElementById("password").value=='')
	{
		alert("パスワードを入力してください");
		document.getElementById('password').focus();
		return false;
	}
	else
	{
		
		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("「パスワードは英数字6文字以上にしてください」");
			document.getElementById('password').focus();
			return false;
		}    
	}
	
	
	 if(!RE_EMAIL.exec(email))
	 {
		alert("正しいメールアドレスではありません");  
		document.getElementById('mail').focus();
		return false;
	  }

	checkDuplicateMail();
	matchMail();
	checkRoomAvailability();
	document.registerfrm.submit();	
}

function popupWindow(path)
 	{ 		
	   var code1= document.getElementById('zip1').value;
	   var code2= document.getElementById('zip2').value;
	   var code=code1+code2;
       mywindow = window.open(''+path+'?code='+code,'mywindow','location=0,status=0,scrollbars=1,height=400,width=400');
    }

function checkDuplicateMail()
{
	var mail = $j("#mail").val();
	
	$j.post("check_duplicate_mail.php",{email:mail}, function (data){
															  
		if(parseInt(data) > 0)
		{
			alert("Email already Exists");
			document.getElementById('mail').focus();
			exit;
		}
	});
}

function matchMail()
{
	var mail = $j("#mail").val();
	var conemail = $j("#conemail").val();
	if(mail != conemail)
	{
		alert("同じメールアドレスを入力下さい");
		document.getElementById('conemail').focus();
		exit;
	}
}
function checkRoomAvailability()
{
	var room_id = $j("#room_id").val();
	var marriage_day =$j("#marriage_day").val();
	var party_day_with_time =$j("#party_day_with_time").val();
	var party_day =$j("#party_day").val();
	var party_room_id = $j("#party_room_id").val();
	
	$j.post("check_room_availability.php",{room_id:room_id,marriage_day:marriage_day,party_room_id:party_room_id,party_day:party_day}, function (data){
		if(parseInt(data) > 0)
		{
			alert("只今、選択できません。");	
			exit;
		}

	});
	
}
function checkUser()
{
	var uname = $j("#user_id").val();
	
	$j.post("check_room_availability.php",{uname:uname}, function (data){
		if(parseInt(data) > 0)
		{
			alert("氏名が重複しております。");
			document.getElementById('user_id').focus();
			exit;
		}

	});
	
}

function reset_form(frm)
{
	var frm_elements = document.getElementById(frm).elements; 
	for(i=0; i<frm_elements.length; i++) 
	{

		field_type = frm_elements[i].type.toLowerCase();
		
		switch(field_type) 
		{
		
			case "text":
			case "password":
			case "textarea":
			case "hidden":
		
				frm_elements[i].value = "";
				
				break;
		
			case "radio":
			case "checkbox":
		
				if (frm_elements[i].checked) {
		
					frm_elements[i].checked = false;
		
				}
				break;
		
			case "select-one":
			case "select-multi":
		
				frm_elements[i].selectedIndex = -1;
				break;
		
			default:
				break;
		
		}
		
	} 
}

function valid_user()
{
	var email = document.getElementById('mail').value;
	
	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	
	if(document.getElementById("man_firstname").value=='')
	{
		alert("新郎の姓を正しく入力してください");
		document.getElementById('man_firstname').focus();
		return false;
	}
	if(document.getElementById("man_lastname").value=='')
	{
		alert("「新郎の名を正しく入力してください」");
		document.getElementById('man_lastname').focus();
		return false;
	}

	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の姓のフリガナを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}

   if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の名のフリガナを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}
	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}

	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の姓のフリガナを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}
    if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の名のフリガナを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}
	if(document.getElementById("marriage_day_with_time").value=='')
	{
		alert("挙式時間を正しく入力してください");
		document.getElementById('marriage_day_with_time').focus();
		return false;
	}
	if(document.getElementById("room_id").value=='')
	{
		alert("必須項目は必ず入力してください。");
		document.getElementById('room_id').focus();
		return false;
	}
	
	if(document.getElementById("religion").value=='')
	{
		alert("挙式種類を選択してください");
		document.getElementById('religion').focus();
		return false;
	}
	
	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}
	if(document.getElementById("party_day_with_time").value=='')
	{
		alert("披露宴時間を正しく入力してください");
		document.getElementById('party_day_with_time').focus();
		return false;
	}
	if(document.getElementById("party_room_id").value=='')
	{
		alert("Party room must not be empty");
		document.getElementById('party_room_id').focus();
		return false;
	}
	
	
	
	if(document.getElementById("password").value=='')
	{
		alert("パスワードを入力してください");
		document.getElementById('password').focus();
		return false;
	}
	else
	{
		
		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("「パスワードは英数字6文字以上にしてください」 ");
			document.getElementById('password').focus();
			return false;
		}    
	}
	if(!RE_EMAIL.exec(email) && email!='')
	 {
		alert("正しいメールアドレスではありません");  
		document.getElementById('mail').focus();
		return false;
	  }

	checkDuplicateMail();
	/*matchMail();
	checkRoomAvailability();*/
	
	document.user_form_register.submit();
}
