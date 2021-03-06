<?php
    // ログイン画面から管理者TOPへ遷移されてしまう
    // ログインしないと画面遷移できないようにする
    // ログインフィルター
    require_once 'admin_login_filter.php';

    // 外部ファイル読込
    require_once 'admin_daos/news_dao.php';
    require_once 'admin_daos/admin_dao.php';
    require_once 'daos/item_dao.php';
    // セッション開始
    // session_start();

    // ログイン者の情報をセッションに保存
    $login_admin = $_SESSION['login_admin'];
    // var_dump($login_admin);
    
    
    // idをGETで取得
    // $idをnullにする
    $id = null;
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    // データベースから全商品を取得
    $items = ItemDAO::get_all_items();
    // var_dump($items);
    // 表示したい画像をさいころを振って決める
    $rand = mt_rand(0, count($items) - 1);
    // print $rand;
    $items = $items[$rand];
    // newsの情報取得
    $news = NewsDAO::get_news_id($id);
    // var_dump($news);
    // viewファイルの表示
    include_once 'admin_views/admin_index_view.php';
?>