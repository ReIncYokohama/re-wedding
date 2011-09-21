<?php
include_once('dbcon.inc.php');
class MailClass extends MessageClass
{

	// UCHIDA EDIT 11/08/19 man_lastname, woman_lastname ⇔ man_firstname, woman_firstnameを一括して変更

	public function MailClass()
	{

	}
	function user_suborder_mail_to_admin($user_id,$print_company_id)
	{
		/*	THIS EMAIL WILL BE SEND TO ADMIN FROM USER TO SEND USER SUBORDER TO PRINT COMPANY
			１　システムからホテルスタッフへ( from:  to: hotelstaff )
			１-１　um-1　click　　sub order 仮発注依頼発生　　sytem　→　hotel staff
			USER => ADMIN
			DOC mail:1
		*/
		$print_company_info = $this :: get_printing_company_info($print_company_id);
		$user_info = $this :: get_user_info($user_id);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);

		$admin_link = ADMIN_LINK ;
		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];

		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
		 	$subject="［ウエディングプラス］仮発注依頼がありました";//'spssp user sub-order mail to admin';


		    $user_suborder_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様から仮発注依頼がありました。
仮発注処理をお願いいたします。
{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------

ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplass@sunpri.com
URL：{$admin_link}


html;

			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_suborder_mail_body);

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}

		}

	}
	function user_print_request_mail_to_admin($user_id,$print_company_id)
	{
		/*	THIS EMAIL WILL BE SEND TO ADMIN FROM USER TO SEND THE USER PRINT REQUEST TO PRINT COMPANY
			１-２ um-2　click　本発注依頼発生　　sytem　→　hotel staff
			USER => ADMIN
			DOC MAIL:5
		*/

		$print_company_info = $this :: get_printing_company_info($print_company_id);
		$user_info = $this :: get_user_info($user_id);

		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);
		$admin_link = ADMIN_LINK ;

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];


		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
			$subject="［ウエディングプラス］本発注依頼がありました";//'spssp user print request mail to admin';

			$user_suborder_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様から本発注依頼がありました。
本発注処理をお願いいたします。
{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------

ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplass@sunpri.com
URL：{$admin_link}

html;

			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_suborder_mail_body);

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}

		}

	}



	function printCompany_upload_admin_notification_mail($user_id,$print_company_id)
	{
		/*  THIS MAIL WILL BE SEND TO USER'S ADMIN WHEN USER'S PRINT COMPANY UPLOADS SOME FILE FOR USER
			PRINT COMPANY => ADMIN
			１-６　print company data up load　印刷会社より印刷イメージのアップロード　　
			sytem　→　hotel staff  ( suborder pdf uplod ok then goes to staff )
			HINTS :  MAil NO : 3
		*/


		$print_company_info = $this :: get_printing_company_info($print_company_id);
		$user_info = $this :: get_user_info($user_id);

		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];

		$admin_link = ADMIN_LINK_FOR_PRINT ;

		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
			$subject="［ウエディングプラス］印刷イメージが出来上がりました";
			//"{$print_company_info['company_name']} Print Company has uploaded file for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様向け席次表印刷イメージが出来上がりました。
ご確認をお願いいたします。

{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplass@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplass@sunpri.com
{$admin_link}

html;

			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//IF END OD STAFF CHECK

	}
	function printCompany_upload_user_notification_mail($user_id,$print_company_id)
	{
		/* 	THIS MAIL WILL BE SEND TO USER WHEN USER'S PRINT COMPANY UPLOADS SOME FILE FOR USER
			PRINT COMPANY => USER
			HINTS :  MAIL NO : 4
			２-３　print company data up load　印刷会社より印刷イメージのアップロード　　sytem　→　user
			( suborder pdf uplod ok then goes to staff )
		 */

		$print_company_info = $this :: get_printing_company_info($print_company_id);
		$user_info = $this :: get_user_info($user_id);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);

		$user_link = MAIN_LINK;

		if($user_info['subcription_mail']=="0" && $user_info['mail'] != "")
		{
			$subject="［ウエディングプラス］印刷イメージが出来上がりました";

			//"{$print_company_info['company_name']} Print Company has uploaded file for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$user_info['man_lastname']}・{$user_info['woman_lastname']} 様

このたびはウエディングプラスをご利用いただき、ありがとうございます。
席次表の印刷イメージが出来上がりました。
ご確認をお願いいたします。
{$user_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplass@sunpri.com
URL：{$user_link}

html;


			if($user_info['mail']!="" && $user_info['mail']!="0")
			{

					$res = $this :: mail_to($to = $user_info['mail'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check

	}

	function process_mail_user_suborder($user_id, $print_company_id, $hotel_name)
	{
		/*	THIS EMAIL WILL BE SEND TO THE PRINT COMPANY WHEN ADMIN SENDS THE USER's SUBORDER TO PRINT COMPANY
			３システムから印刷会社へ　　sytem　→　printing company
			３-１　hm-1　click　仮発注　sub order  (admin panel )
			ADMIN => PRINT COMPANY
			HINTS : MAIL NO : 2
		*/


		$print_company_info = $this :: get_printing_company_info($print_company_id);

		$user_info = $this :: get_user_info($user_id);
		$user_plan_info = $this :: get_user_plan_info($user_id);

		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);

		$print_company_download_link = $this :: get_print_company_download_link( $user_info['id'] , $print_company_info['id'] );
		$print_company_upload_link = $this :: get_print_company_upload_link( $user_info['id'] , $print_company_info['id'] );

		if($user_plan_info['print_size']==1){$print_size="A3";}elseif($user_plan_info['print_size']==2){$print_size="B4";}
		if($user_plan_info['dowload_options']==1){$dowload_options="席次表";}elseif($user_plan_info['dowload_options']==2){$dowload_options="席札";}elseif($user_plan_info['dowload_options']==3){$dowload_options="席次表・席札";}
		if($user_plan_info['print_type']==1){$print_type="横";}elseif($user_plan_info['print_type']==2){$print_type="縦";}
		$party_day_with_time = explode(":", $user_info['party_day_with_time']);
		$party_day= $this :: japanyDateFormate_for_mail($user_info['party_day']);

		$subject="［ウエディングプラス］{$hotel_name}より仮発注依頼がありました";//'spssp user sub-order mail';

		$user_suborder_mail_body =<<<html
{$print_company_info['company_name']}　様

いつもお世話になっております。

{$hotel_name} より仮発注依頼がありました。
発注内容は以下のとおりです。


仮発注依頼書
ホテル名：{$hotel_name}
新郎名：{$user_info['man_lastname']} {$user_info['man_firstname']} 様
新婦名：{$user_info['woman_lastname']} {$user_info['woman_firstname']} 様
披露宴日時：{$party_day} {$party_day_with_time[0]}:{$party_day_with_time[1]}
商品区分：{$dowload_options}
商品名：{$user_plan_info['product_name']}
サイズ：{$print_size}
配置：{$print_type}
担当者名：{$staff_info['name']} 様

以下のURLからログインし、データのダウンロード・校正紙の作成をお願いいたします。
また、校正紙を作成されましたら、同じ画面よりログインし、アップロードをお願いいたします。
・データーダウンロードURL
{$print_company_download_link}
・データーアップロードURL
{$print_company_upload_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplass@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------

ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplass@sunpri.com

html;


		if($print_company_info['email']!="" && $print_company_info['email']!="0")
		{
				$res = $this :: mail_to($to = $print_company_info['email'], $subject , $mailbody = $user_suborder_mail_body)	;

				if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

		}
		else
		{
			return $err = 30;exit;
		}

	}


	function process_mail_user_print_request($user_id,$print_company_id, $hotel_name)
	{
		/*  THIS EMAIL WILL BE SEND TO THE PRINT COMPANY WHEN ADMIN SENDS THE USER PRINT REQUEST TO THE PRINT COMPANY
			ADMIN => PRINT COMPANY
			HINTS : MAIL NO : 6
			３-２  hm-2　click 本発注 real order　　sytem　→　printing company
		*/
		$print_company_info = $this :: get_printing_company_info($print_company_id);

		$user_info = $this :: get_user_info($user_id);
		$user_plan_info = $this :: get_user_plan_info($user_id);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);
		$print_company_download_link = $this :: get_print_company_download_link( $user_info['id'] , $print_company_info['id'] );
		$print_company_upload_link = $this :: get_print_company_upload_link( $user_info['id'] , $print_company_info['id'] );

		if($user_plan_info['print_size']==1){$print_size="A3";}elseif($user_plan_info['print_size']==2){$print_size="B4";}
		if($user_plan_info['dowload_options']==1){$dowload_options="席次表";}elseif($user_plan_info['dowload_options']==2){$dowload_options="席札";}elseif($user_plan_info['dowload_options']==3){$dowload_options="席次表・席札";}

		if($user_plan_info['day_limit_1_to_print_com']!="0"){ $day_limit_1_to_print_com = "席次表部数：".$user_plan_info['day_limit_1_to_print_com']." 部";}
		if($user_plan_info['day_limit_2_to_print_com']!="0"){ $day_limit_2_to_print_com = "席札部数：".$user_plan_info['day_limit_2_to_print_com']." 部";}
		if($user_plan_info['print_type']==1){$print_type="横";}elseif($user_plan_info['print_type']==2){$print_type="縦";}

		$party_day= $this :: japanyDateFormate_for_mail($user_info['party_day']);
		$party_day_with_time = explode(":", $user_info['party_day_with_time']);


		$subject="［ウエディングプラス］{$hotel_name}より本発注依頼がありました";//'spssp user PRINTING REQUEST TO PRINT COMPANY mail';

		$user_suborder_mail_body =<<<html
{$print_company_info['company_name']} 様

いつもお世話になっております。

{$hotel_name} より本発注依頼がありました。
発注内容は以下のとおりです。

本発注依頼書
ホテル名：{$hotel_name}
新郎名：{$user_info['man_lastname']} {$user_info['man_firstname']} 様
新婦名：{$user_info['woman_lastname']} {$user_info['woman_firstname']} 様
披露宴日時：{$party_day} {$party_day_with_time[0]}:{$party_day_with_time[1]}
商品区分：{$dowload_options}
商品名：{$user_plan_info['product_name']}
サイズ：{$print_size}
配置：{$print_type}
{$day_limit_1_to_print_com}
{$day_limit_2_to_print_com}
担当者名：{$staff_info['name']}様


以下のURLからデータをダウンロードし、印刷物の作成をお願いいたします。
{$print_company_download_link}


▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------

ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com

html;

		if($print_company_info['email']!="" && $print_company_info['email']!="0")
		{
				$res = $this :: mail_to($to = $print_company_info['email'], $subject , $mailbody = $user_suborder_mail_body)	;

				if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

		}
		else
		{
			return $err = 30;exit;
		}

	}

	function process_mail_user_gift_daylimit_request($user_id)
	{

		/*  THIS EMAIL WILL BE SEND TO ADMIN FROM USER TO Allow GIFT DAY LIMIT REQUEST
			mail no:7;
			User => Admin
		*/

		$user_info = $this :: get_user_info($user_id);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);
		$admin_link = ADMIN_LINK ;

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];

		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
			$subject="［ウエディングプラス］引出物発注依頼がありました";
			//"ADMIN has been prossessesd gift day limit for {$user_info['man_lastname']} {$user_info['man_firstname']}";

		$user_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様から引出物発注依頼がありました。
引出物発注処理をお願いいたします。
{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com
URL：{$admin_link}

html;


			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check
	}


	function sekiji_day_limit_over_admin_notification_mail($user_id)
	{
		/*
			SYstem => ADMIN
			DOC mail: 8
		*/

		$user_info = $this :: get_user_info($user_id);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);

		$admin_link = ADMIN_LINK ;

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];



		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
			$subject="［ウエディングプラス］席次表本発注締切日を過ぎています";
			//"ADMIN has been prossessesd gift day limit for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様の席次表本発注締切日を過ぎています。
お客様にご確認の上、至急本発注処理をお願いいたします。
{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com
URL：{$admin_link}

html;


			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check
	}
	function sekiji_day_limit_over_user_notification_mail($user_id)
	{
		/*
			SYSTEM => USER
			DOC MAIL:10
		*/

		$user_info = $this :: get_user_info($user_id);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);

		$user_link = MAIN_LINK;


		if($user_info['subcription_mail']=="0" && $user_info['mail'] != "")
		{
			$subject="［ウエディングプラス］席次表印刷締切日を過ぎています";

			//"{$print_company_info['company_name']} Print Company has uploaded file for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$user_info['man_lastname']}・{$user_info['woman_lastname']} 様

このたびはウエディングプラスをご利用いただき、ありがとうございます。
席次表印刷締切日を過ぎております。
至急印刷発注依頼作業をお願いいたします。
ご不明な点がございましたら、ホテル担当者までご連絡ください
{$user_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com
URL：{$user_link}

html;


			if($user_info['mail']!="" && $user_info['mail']!="0")
			{

					$res = $this :: mail_to($to = $user_info['mail'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check
	}
	function hikidemono_day_limit_over_admin_notification_mail($user_id)
	{
		/*
			SYstem => ADMIN
			DOC mail: 9
		*/

		$user_info = $this :: get_user_info($user_id);
		$staff_info = $this :: get_user_staff_info($user_info['stuff_id']);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);

		$admin_link = ADMIN_LINK ;

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day00=$party_date_array[1]."/".$party_date_array[2];

		if($staff_info['subcription_mail']=="0" && $staff_info['email'] != "")
		{
			$subject="［ウエディングプラス］引出物本発注締切日を過ぎています";
			//"ADMIN has been prossessesd gift day limit for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$staff_info['name']} 様

いつもお世話になっております。

{$party_day00} {$user_info['man_lastname']}・{$user_info['woman_lastname']}様の引出物本発注締切日を過ぎています。
お客様にご確認の上、至急引出物処理をお願いいたします。
{$admin_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com
URL：{$admin_link}

html;


			if($staff_info['email']!="" && $staff_info['email']!="0")
			{
					$res = $this :: mail_to($to = $staff_info['email'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check
	}
	function hikidemono_day_limit_over_user_notification_mail($user_id)
	{
		/*
			SYSTEM => USER
			DOC MAIL:11
		*/

		$user_info = $this :: get_user_info($user_id);
		//$user_male_respect_info = $this :: get_user_respect_info($user_info['man_respect_id']);
		//$user_femaile_respect_info = $this :: get_user_respect_info($user_info['woman_respect_id']);


		$user_link = MAIN_LINK;

		if($user_info['subcription_mail']=="0" && $user_info['mail'] != "")
		{
			$subject="［ウエディングプラス］引出物締切日を過ぎています";

			//"{$print_company_info['company_name']} Print Company has uploaded file for {$user_info['man_lastname']} {$user_info['man_firstname']}";

			$user_mail_body =<<<html
{$user_info['man_lastname']}・{$user_info['woman_lastname']} 様

このたびはウエディングプラスをご利用いただき、ありがとうございます。
引出物の締切日を過ぎております。
至急引出物発注依頼作業をお願いいたします。
ご不明な点がございましたら、ホテル担当者までご連絡ください。
{$user_link}

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.com

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

--------------------------------------------
ウエディングプラス
(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.com
URL：{$user_link}

html;


			if($user_info['mail']!="" && $user_info['mail']!="0")
			{

					$res = $this :: mail_to($to = $user_info['mail'], $subject , $mailbody = $user_mail_body)	;

					if($res==1){ return  $mes = 6;/*success*/ }else{ return  $err = 28;/*error*/}

			}
			else
			{
				return $err = 30;exit;
			}
		}//if end od staff check
	}
	function mail_to( $to , $subject , $mailbody )
	{

		/*echo $to."<br>";
		echo $subject."<br>";
		echo $mailbody."<br>";
		echo "<br><br>";*/


		$header='From:'."weddingplus@sunpri.com"." \r\n";
		$header.='Content-Type:text/plain; charset=utf-8'."\r\n";
		//$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		$subject = base64_encode(mb_convert_encoding($subject,"JIS","UTF8"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject.'?=';

		$user_body=$mailbody;

		///////MAIL TO USER /////////////
		if(@mail($to, $usersubject, $user_body, $header))
		{
			return 1;
		}
		else
		{
			return 2;
		}



	}



}//END OF CLASS_MAIL
?>
