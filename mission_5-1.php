<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>みんなの自慢できることは？</h1>
    <?php
    ///DB接続設定
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
    
     ///DB内にテーブルを作成
    $sql="CREATE TABLE IF NOT EXISTS board"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."date DATETIME,"
    ."password TEXT"
    .");";
    $stmt=$pdo->query($sql);
    
     ///データを入力
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){ ///名前とコメントとパスワードを記入しているとき
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $password=$_POST["password"];
        
        if(!empty($_POST["enum"])){ ///enumに値があるとき（編集モードで）
            $edit=$_POST["enum"]; ///変更する投稿番号
            
            $sql='UPDATE board SET name=:name, comment=:comment, date=:date, password=:password WHERE id=:edit';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':edit', $edit, PDO::PARAM_INT);
            $stmt->execute();
            
        }else{ ///enumに値がないとき（新規投稿のとき）
            $sql=$pdo->prepare("INSERT INTO board (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->execute();
        }
    }
    
    ///データ削除
    if(!empty($_POST["delete"]) && !empty($_POST["password1"])){
        $delete=$_POST["delete"]; //削除する投稿番号
        $password1=$_POST["password1"];
         ///削除対象番号と投稿番号が等しく、パスワードも等しい
            $sql='delete from board where id=:delete && password=:password1'; 
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':delete', $delete, PDO::PARAM_INT);
            $stmt->bindParam(':password1', $password1, PDO::PARAM_STR);
            $stmt->execute();
    }
    
    ///データ編集  
    if(!empty($_POST["editnum"]) && !empty($_POST["password2"])){ ///編集対象番号とパスワードを記入しているとき
        $editnum=$_POST["editnum"]; ///編集対象番号
        $password2=$_POST["password2"];
            
        $sql='SELECT*FROM board WHERE id=:editnum && password=:password2'; ///入力したデータの表示
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':editnum', $editnum, PDO::PARAM_INT);
        $stmt->bindParam(':password2', $password2, PDO::PARAM_STR);
        $stmt->execute();
        $results=$stmt->fetchAll();
        foreach($results as $row){
            $enum=$row['id'];
            $edit1=$row['name'];
            $edit2=$row['comment'];
            $date=$row['date'];
        }
    }
    
    ?>
    
    <form action=""method="post">
        <input type="text" name="name" placeholder="名前入力欄" value="<?php if(isset($edit1)) {echo $edit1;}?>"><br>
        <input type="text" name="comment" placeholder="コメント入力欄" value="<?php if(isset($edit2)) {echo $edit2;}?>"><br>
        <input type="text" name="password" placeholder="パスワード入力欄"><br>
        <input type="submit" name="submit" value="送信"><br>
        <input type="hidden" name="enum" value="<?php if(isset($enum)) {echo $enum;}?>"><br>   <!--編集している番号（編集モードか判断）-->
    </form>
    <form action=""method="post">
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="password1" placeholder="パスワード入力欄"><br>
        <input type="submit" name="reset" value="削除"><br>
    </form>
    <form action=""method="post">
        <input type="number" name="editnum" placeholder="編集対象番号"><br>
        <input type="text" name="password2" placeholder="パスワード入力欄"><br>
        <input type="submit" name="esub" value="編集"><br>
    </form>
    
    <?php
    $sql='SELECT*FROM board'; ///入力したデータの表示
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
    ?>
</body>
</html>