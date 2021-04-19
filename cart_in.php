<?php
    // 外部ファイル読込
    require_once 'models/customer.php';
    require_once 'daos/cart_dao.php';
    require_once 'daos/item_dao.php';
    // var_dump($_POST);    
    // セクション開始
    session_start();

    // ログイン者の情報取得
    $login_customer = $_SESSION['login_customer'];
    // var_dump($login_customer);
    // 商品詳細ページにて選択された商品情報取得
    $cart = $_SESSION['item'];
    // var_dump($cart);

    // 選択された情報を保存
    $customer_id = $login_customer->id;
    // $customer_id = $_POST['customer_id'];
    // var_dump($customer_id);
    $item_id = $_POST['item_id'];
    $item_stock = $_POST['item_stock'];
    $number = $_POST['number'];
    // var_dump($_POST);
    // var_dump($customer_id);
    
    // ログイン顧客が $item_id の商品を既にカートに入れているか判定
    $cart = cartDAO::find_cart($customer_id, $item_id);
    // var_dump($cart);
    
    // もし、そんなカートが既にcartsテーブルに存在すれば、
    if($cart !== false){
        // カート情報の更新処理
        // cartDAO を使ってカートの個数を変更
        // cart番号と変更後の個数を引数として渡し updateメソッド実行
        // var_dump($cart->id);
        // var_dump($cart->number + $number);
        CartDAO::update($cart->id, $cart->number + $number);
        $_SESSION['number_message'] = 'カート番号' . $cart->id . 'の商品の個数を' . $cart->number + $number . '個に変更しました';
        header('Location: cart.php');
        exit;
        
    }else{
        // 既にカートに商品が入ってあればカートインスタンス取得する
        // cart命の誕生
        $cart = new Cart($customer_id, $item_id, $item_stock, $number);
        // var_dump($cart);
         // カートに1件登録
        $cart = CartDAO::insert($cart);
        $_SESSION['carts'] = $cart;
        var_dump($carts);
        $_SESSION['cart_message'] = '商品をカートに追加しました';
        header('Location: cart.php');
        exit;
    }

?>