RE_PASS   = new RegExp(/[A-Za-z0-9]$/);
function validForm(){

   // get all the inputs into an array.
    //var elem   = document.getElementById('registerfrm').elements;
	var str="";
	var email = document.getElementById('mail').value;

	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);

	if(document.getElementById("man_lastname").value=='')
	{
		alert("Man Lastname must not be empty");
		document.getElementById('man_lastname').focus();
		return false;
	}
	if(document.getElementById("man_firstname").value=='')
	{
		alert("Man Firstname must not be empty");
		document.getElementById('man_firstname').focus();
		return false;
	}
	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("Man Furi last name must not be empty");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}
	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("Man Furi first name must not be empty");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_lastname").value=='')
	{
		alert("Woman Lastname must not be empty");
		document.getElementById('woman_lastname').focus();
		return false;
	}
	if(document.getElementById("woman_firstname").value=='')
	{
		alert("Woman Firstname must not be empty");
		document.getElementById('woman_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("Woman Furi last name must not be empty");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("Woman Furi first name must not be empty");
		document.getElementById('woman_furi_firstname').focus();
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
		alert("Marriage day must not be empty");
		document.getElementById('marriage_day').focus();
		return false;
	}
	/*if(document.getElementById("marriage_day_with_time").value=='')
	{
		alert("Marriage day with time must not be empty");
		document.getElementById('marriage_day_with_time').focus();
		return false;
	}*/
	if(document.getElementById("room_id").value=='')
	{
		alert("Marriage room must not be empty");
		document.getElementById('room_id').focus();
		return false;
	}

	if(document.getElementById("religion").value=='')
	{
		alert("Religion must not be empty");
		document.getElementById('religion').focus();
		return false;
	}

	if(document.getElementById("party_day").value=='')
	{
		alert("Party day must not be empty");
		document.getElementById('party_day').focus();
		return false;
	}
	/*if(document.getElementById("party_day_with_time").value=='')
	{
		alert("Party day with time must not be empty");
		document.getElementById('party_day_with_time').focus();
		return false;
	}*/
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
		alert("telephone number must not be empty");
		document.getElementById('tel').focus();
		return false;
	}
	if(document.getElementById("mail").value=='')
	{
		alert("mail  must not be empty");
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
		alert("password  must not be empty");
		document.getElementById('password').focus();
		return false;
	}
	else
	{

		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードは半角英数字6文字以上で入力ください ");
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

	//checkDuplicateMail();
	//matchMail();
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

function email_validate(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
       return false;
   }
   else
   {
   		return true;
   }
}
function checkDuplicateMail(user_id)
{
	var mail = $j("#mail").val();


	$j.post("http://re-dev.sakura.ne.jp/dev2/hotel11/admin/ajax/check_duplicate_mail.php",{email:mail,user_id:user_id}, function (data){


		if(parseInt(data) > 0)
		{
				alert("");
				document.getElementById('mail').focus();
				return false;
	   }
	   else
	   {
			return 2;
	   }

	});
}

function matchMail()
{
	var mail = $j("#mail").val();
	var conemail = $j("#con_mail").val();
	if(mail != conemail)
	{
		alert("PCメールアドレスが一致しません。再度入力してください。");
		document.getElementById('con_mail').focus();
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
			alert("部屋は既に選択されている日付に予約されています");
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
			alert("ユーザーは既に存在します");
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


function valid_user(user_id)
{
	var email = document.getElementById('mail').value;
	var com_email = document.getElementById('con_mail').value;

	var reg = /^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/;

	if(document.getElementById("man_lastname").value=='')
	{
		alert("新郎の名を正しく入力してください"); // UCHIDA EDIT 11/08/05 メッセージを変更
		document.getElementById('man_lastname').focus();
		return false;
	}
	if(document.getElementById("man_firstname").value=='')
	{
		alert("新郎の姓を正しく入力してください");
		document.getElementById('man_firstname').focus();
		return false;
	}

	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の姓のふりがなを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}

   var str = document.getElementById("man_furi_firstname").value;
   if( str.match( /[^ぁ-ん\s]+/ ) ) {
      alert("新郎の姓のふりがなを正しく入力してください");
	  document.getElementById('man_furi_firstname').focus();
	  return false;
   }


	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の名のふりがなを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}

   var str1 = document.getElementById("man_furi_lastname").value;
   if( str1.match( /[^ぁ-ん\s]+/ ) ) {
      alert("新郎の名のふりがなを正しく入力してください");
	  document.getElementById('man_furi_lastname').focus();
	  return false;
   }


	if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}

	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}

	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の姓のふりがなを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}

    var str2 = document.getElementById("woman_furi_firstname").value;
   if( str2.match( /[^ぁ-ん\s]+/ ) ) {
      alert("新婦の姓のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_firstname').focus();
	  return false;
   }


	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の名のふりがなを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

    var str3 = document.getElementById("woman_furi_lastname").value;
   if( str3.match( /[^ぁ-ん\s]+/ ) ) {
      alert("新婦の名のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_lastname').focus();
	  return false;
   }



	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}

	if(document.getElementById("marriage_hour").value >23)
	{
		alert("24時間以内で入力してください");
		document.getElementById('marriage_hour').focus();
		return false;
	}

    var str4 = document.getElementById("marriage_hour").value;
   if( str4.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間は半角数字で入力ください");
	  document.getElementById('marriage_hour').focus();
	  return false;
   }



	if(document.getElementById("marriage_minute").value >59)
	{
		alert("59分以上は入力できません");
		document.getElementById('marriage_minute').focus();
		return false;
	}

    var str5 = document.getElementById("marriage_minute").value;
   if( str5.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間は半角数字で入力ください");
	  document.getElementById('marriage_minute').focus();
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

	if(document.getElementById("party_hour").value >23)
	{
		alert("24時間以内で入力してください");
		document.getElementById('party_hour').focus();
		return false;
	}

   var str6 = document.getElementById("party_hour").value;
   if( str6.match( /[^0-9\s]+/ ) ) {
      alert("披露宴開始時間は半角数字で入力ください");
	  document.getElementById('party_hour').focus();
	  return false;
   }

	if(document.getElementById("party_minute").value >59)
	{
		alert("59分以上は入力できません");
		document.getElementById('party_minute').focus();
		return false;
	}

   var str7 = document.getElementById("party_minute").value;
   if( str7.match( /[^0-9\s]+/ ) ) {
      alert("披露宴開始時間は半角数字で入力ください");
	  document.getElementById('party_minute').focus();
	  return false;
   }

	if(document.getElementById("party_room_id").value=='')
	{
		alert("挙式会場名を入力してください");
		document.getElementById('party_room_id').focus();
		return false;
	}
	if(document.getElementById("user_id").value=='')
	{
		alert("ログインIDを入力してください");
		document.getElementById('user_id').focus();
		return false;
	}

	var radio3  = document.user_form_register.subcription_mail;

// UCHIDA EDIT 11/08/05 メッセージを変更
	if(radio3[0].checked)
	{
		if(email=='')
		{
			alert("メールアドレスが未入力です");
			document.getElementById('mail').focus();
			return false;
		}
		else
		{
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('mail').focus();
				return false;
			}

			if(com_email!=email)
			{
				alert("メールアドレス確認用を正しく入力してください");
				document.getElementById('con_mail').focus();
				return false;
			}
		}
	}else{

		if(email !='')
		{
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('mail').focus();
				return false;
			}

			if(com_email != email)
			{
				alert("メールアドレス確認用を正しく入力してください");
				document.getElementById('con_mail').focus();
				return false;
			}
		}
	}
// UCHIDA EDIT 11/08/05 パスワードは入力対象ではなくなったので、チェックを外す
/*
var pass = document.getElementById("password").value;
if(document.getElementById("password").value=='')
	{
		alert("パスワードは半角英数字6文字以上で入力ください ");
		document.getElementById('password').focus();
		return false;
	}
	else
	{

		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードは半角英数字6文字以上で入力ください ");
			document.getElementById('password').focus();
			return false;
		}
	}

	if(!RE_PASS.exec(pass))
	 {
		alert("パスワードは英数字 を入力してください");
		document.getElementById('password').focus();
		return false;
	  }
*/
	//checkDuplicateMail();
	/*matchMail();
	checkRoomAvailability();*/
	if(document.getElementById("room_id").value==document.getElementById("current_room_id").value)
	document.user_form_register.submit();
	else
		{
			if(confirm("披露宴会場を変更すると、現在の設定が削除されます。変更してよろしいですか？"))
			document.user_form_register.submit();
		}

}