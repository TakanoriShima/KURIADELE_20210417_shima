<?php
    // 外部ファイル読込
    require_once 'models/cart.php';
    // データベースを扱う便利屋さん
    class CartDAO{
        // データベースと接続を行うメソッド
        private static function get_connection(){
            
            // データベース接続情報      
            $dsn = "mysql:host=localhost;dbname=KURIADELE";
            $db_username = "root";
            $db_password = "";
        
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // 失敗したら例外を投げる
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,   //デフォルトのフェッチモードはクラス
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',   //MySQL サーバーへの接続時に実行するコマンド
            );
        
            // データベースを扱う万能の神様誕生
            $pdo = new PDO($dsn, $db_username, $db_password, $options);
            
            // 神様はいあげる
            return $pdo;
        }
    
        // データベースとの切断を行うメソッド
        private static function close_connection($pdo, $stmp){
            // 神様さようなら
            $pdo = null;
            // 実行結果さようなら
            $stmp = null;
        }
    
        // カートに1件登録するメソッド
        public static function insert($cart){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();
                // INSERT文を実行する準備（名前、年齢はわざとあやふやにしておく）
                $stmt = $pdo -> prepare("INSERT INTO carts(customer_id, item_id, item_stock, number) VALUES(:customer_id, :item_id, :item_stock, :number)");
                // バインド処理（あやふやだった箇所を実データで埋める）
                $stmt->bindValue(':customer_id', $cart->customer_id, PDO::PARAM_INT);
                $stmt->bindValue(':item_id', $cart->item_id, PDO::PARAM_INT);
                $stmt->bindValue(':item_stock', $cart->item_stock, PDO::PARAM_INT);
                $stmt->bindValue(':number', $cart->number, PDO::PARAM_INT);
                
                // INSERT文本番実行
                $stmt->execute();
                // print 'OK';
                return "カートに追加しました";
                
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
               self::close_connection($pdo, $stmp); 
            }
        }
        
        // 顧客番号を指定した場合の全カート情報を取得するメソッド
        public static function get_my_carts($customer_id){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();
                // SELECT文を実行する準備
                $stmt = $pdo -> prepare("SELECT * FROM carts WHERE customer_id=:customer_id");

                // バインド処理（あやふやだった箇所を実データで埋める）
                $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
                
                // SELECT文本番実行
                $stmt->execute();
                
                // フェッチの結果を、Userクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cart');
                
                // 全カート情報を取得
                $my_carts = $stmt->fetchAll();
                return $my_carts;
                
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
               self::close_connection($pdo, $stmp); 
            }
        }
        // 合計金額
        public static function get_total_price($carts){
            $pdo = null;
            $stmp = null;
            $total = 0;
            foreach($carts as $cart){
                $total += $cart->get_item()->price * $cart->number;
            }
            return $total;
        }
        // 購入時該当商品の在庫を 減らす
        public static function decrement_stock($cart){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();
                // INSERT文を実行する準備（データはわざとあやふやにしておく）
                $stmt = $pdo -> prepare("UPDATE carts SET item_stock=(item_stock - :number) WHERE customer_id=:customer_id");
                
                // バインド処理（あやふやだった箇所実データで埋める）
                $stmt->bindParam(':number', $cart->number, PDO::PARAM_INT);
                $stmt->bindParam(':customer_id', $cart->item_id, PDO::PARAM_INT);
                
                // UPDATE文本番実行
                $stmt->execute();
    
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
               self::close_connection($pdo, $stmp); 
            }
        } 
        
        // 指定した番号のカート情報を削除するメソッド
        public static function delete_cart($id){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();
                // DELETE文を実行する準備
                $stmt = $pdo -> prepare("DELETE FROM carts WHERE id=:id");
                // バインド処理（あやふやだった箇所を実データで埋める）
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                // DELETE文本番実行
                $stmt->execute();
                return '削除しました';
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
               self::close_connection($pdo, $stmp); 
            }
        }
        // 選択した商品の個数を変更するメソッド
        public static function update($id, $number){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();
                // update文を実行する準備（数字はわざとあやふやにする
                $stmt= $pdo -> prepare('UPDATE carts SET number=:number WHERE id=:id');
                // バインド処理（あやふやだった数字を実データで埋める）
                $stmt->bindValue(':number', $number, PDO::PARAM_INT);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                // update本番実行
                $stmt->execute();
                // print 'OK';
                return '商品個数変更致しました';
                
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
                self::close_connection($pdo, $stmp);
            }
        }
        // 顧客番号を指定した場合の item_id を取得するメソッド
        public static function find_cart($customer_id, $item_id){
            $pdo = null;
            $stmp = null;
            try{
                // データベースに接続する神様取得
                $pdo = self::get_connection();

                // update文を実行する準備（数字はわざとあやふやにする
                $stmt= $pdo -> prepare('SELECT * FROM carts WHERE customer_id=:customer_id AND item_id=:item_id');
                                     
                // バインド処理（あやふやだった数字を実データで埋める）
                $stmt->bindValue(':item_id', $item_id, PDO::PARAM_INT);
                $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
                
                // SELECT文本番実行
                $stmt->execute();
                
                // フェッチの結果を、Userクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Cart');
                
                //カート情報を取得
                $cart = $stmt->fetch();
                return $cart;
                
            }catch(PDOException $e){
                
                return "問題が発生しました<br>" . $e->getMessage();
                
            }finally{
               self::close_connection($pdo, $stmp); 
            }
        }

    }
?>