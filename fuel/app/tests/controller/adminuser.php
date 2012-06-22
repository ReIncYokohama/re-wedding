<?php
/**
 * @group App
 * @group Admin
 * @group Controller
 */

class Test_Controller_Adminuser extends Core_Test
{
  public function testCreate(){
    //印刷会社でログイン
    $this->superLogin();

    //招待者の新規作成画面をクリック
    $this->_session->element('id','new_user_button')->click();

    //招待者の新規作成画面をクリックしたことを確認
    $this->assertEquals($this->url."/admin/user_info_allentry.php", $this->_session->url());

    //10日後
    $date = getdate(mktime(0,0,0,date("m"),date("d")+10,date("Y")));
    //披露宴日の入力
    $this->ajaxDateClick("party_day",$date["mday"],$date["mon"],$date["year"]);

    //披露宴日の時間
    $this->text("party_hour","12");
    $this->text("party_minute","30");

    //新郎新婦名の入力
    $this->text("man_lastname","鈴木");
    $this->text("man_firstname","隆");
    $this->text("man_furi_lastname","すずき");
    $this->text("man_furi_firstname","たかし");
    $this->text("woman_lastname","佐藤");
    $this->text("woman_firstname","洋子");
    $this->text("woman_furi_lastname","さとう");
    $this->text("woman_furi_firstname","ひろこ");

    //披露宴会場 4番目を選択
    $this->select_by_order("room_id",3);

    //挙式日の入力
    //8日後
    $date = getdate(mktime(0,0,0,date("m"),date("d")+8,date("Y")));
    //披露宴日の入力
    $this->ajaxDateClick("marriage_day",$date["mday"],$date["mon"],$date["year"]);

    //挙式日の時間
    $this->text("marriage_hour","11");
    $this->text("marriage_minute","30");

    //挙式種類の入力
    $this->select_by_order("religion",2);

    //挙式会場の入力
    $this->text("party_room_id","挙式会場A");

    //メールアドレスの入力
    $this->text("mail","kubonagarei@gmail.com");
    $this->text("con_mail","kubonagarei@gmail.com");
    
    //メールの受信にクリック
    //can_subscribe_mail cant_subscribe_mail
    $this->click_check_box("can_subscribe_mail");

    //スタッフの選択 6番目
    $this->select_by_order("stuff_id",4);

    //卓名変更
    //cant_change_table_name can_change_table_name
    $this->click_check_box("can_change_table_name");

    //商品名の入力
    $this->text("product_name","商品名A");

    //商品区分 0 席次表 席札 席次表・席札
    $this->select_by_order("dowload_options",0);

    //サイズ/タイプ 0 A3 1 B4
    $this->select_by_order("print_size",1); 

    //0 横 1 縦
    $this->select_by_order("print_type",1); 
    
    //本発注締切日
    $this->text("confirm_day_num","7");
    
    //席次表編集利用制限日
    $this->text("limitation_ranking","7");
    
    //印刷会社
    $this->select_by_order("print_company",0);
    
    //引出物商品
    $this->text("item1","引き出物A");
    
    //引出物グループ
    $this->text("name_group1","A");
    
    //引出物締切日
    $this->text("order_deadline","7");
    
    //子供料理
    $this->text("menu_child1","子供A");

    //送信ボタンをクリック
    $this->_session->element('id','user_info_submit')->click();
    
    //アラートが一致
    $this->assertEquals("新しいお客様挙式情報が登録されました", $this->_session->alert_text());
    
    //アラートをOKする
    $this->_session->accept_alert();
    
    //5秒待つ
    $this->_session->timeouts()->implicit_wait(5000);
    
    //登録されたことを確認
    $user = Model_User::recent();
    $this->assertEquals($this->url."/admin/user_info_allentry.php?user_id=".$user->id, $this->_session->url());
        
  }
  
  
}