<?php
    // ログインフィルター
    require_once 'login_filter.php';
    // 外部ファイル読込
    require_once 'cart_dao.php';
    require_once 'customer_dao.php';
    require_once 'purchase_dao.php';
    require_once 'item_dao.php';
    // セッション開始
    session_start();
    
    $login_customer = $_SESSION['login_customer'];
    
    
    $my_carts = CartDAO::get_my_carts($login_customer->id);
    

    // var_dump($my_carts);
    //トランザクション処理を開始
    // $pdo = CartDAO::get_connection()
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->beginTransaction();
    
    foreach($my_carts as $cart){
        
        $purchase = new Purchase($cart->customer_id, $cart->item_id, $cart->number);
        // var_dump($purchase);
        // 購入履歴登録
        PurchaseDAO::insert($purchase);
        // 在庫数減少
        ItemDAO::decrement_stock($cart);
         // カート情報削除
        CartDAO::delete_cart($cart->id);
    }
?>
<!doctype html>
<html lang='ja'>
    <head>
        <meta charset='UTF-8'>
        <title>最終確認</title>
        <link rel='stylesheet' href='index.css'>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    </head>
    <body>
        <div class='container-fluid sticky-top'>
            <div class='row header'>
                <a href='mypage.php' class='logo'><span class='col-lg-2 '>KURIADELE</span></a>
                <span class='offset-lg-4 col-lg-3 px-0 span_c'>
                    <a href='login_contact.php' class='span_d'>お問い合わせ</a>
                    <a href='carts.php' class='span_d'>カート</a>
                    <a href='purchases.php' class='span_d'>購入履歴</a>
                    <a href='logout.php' class='span_d'>ログアウト</a>
                </span>
                
                <span class='col-lg-1 px-0 info'>
                    <form method='POST' action='search.php' class='info'>
                        <input type='search' name='name'/>
                        <input type='submit' value='検索'/>
                    </form>
                
            
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class='dropdown-item' href='#'><a href='login_company_philosophy.php'>KURIADELEについて</a>
                        <a class='dropdown-item' href='#'><a href='login_product.php'>取扱商品</a>
                        <a class='dropdown-item' href='#'><a href='login_contact.php'>サポート</a>
                    </div>
                </span>
            </div>
        </div>
        
        <div class='customer_1'>ご注文ありがとうございました。</div>
            
        <div class='customer_1'>またどうぞよろしくお願い致します。</div>
        
        <a href='mypage.php'>
            <div class='corporation_1'>TOPページへ戻る</div>
        </a>
        
        <div class='footer '>
            <ul><span>KURIADELEについて</span><br>
                <li><a href='login_company_philosophy.php'>企業紹介</a></li>

            </ul>
            <ul><span>取扱商品</span>
                <li><a href='login_product.php'>商品一覧</a></li>
            </ul>
            <ul><span>サポート</span>
                <li><a href='login_contact.php'>お問い合わせ</a></li>
                <li><a href='login_change.php'>お客様情報変更</a></li>
            </ul>
            <!--<ul><span>SNSアカウント</span>-->
            <!--</ul>-->
            
        </div>
    <script type="text/javascript" src="JavaScript2.js"></script>
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js' integrity='sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js' integrity='sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN' crossorigin='anonymous'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' integrity='sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV' crossorigin='anonymous'></script>
    </body>
</thml>
