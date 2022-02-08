<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>題目</h1>
    <?php
    ///DB接続設定
    $dsn='mysql:dbname=tb230906db;host=localhost';
    $user='tb-230906';
    $password='D8dATyStaZ';
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
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){ ///名前とコメントとパスワードを記入しているとき
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass"]; 
    
        if(!empty($_POST["enum"])){ ///enumに値があるとき（編集モードで）
            $edit=$_POST["enum"]; ///変更する投稿番号
            
            if($id==$edit){ ///投稿番号と編集番号が等しい
            $sql='UPDATE board SET name=:name, comment=:comment, date=:date, pass=:pass, WHERE edit=:edit';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':edit', $edit, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
　　        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            }else{
                $sql='UPDATE board SET name=:name, comment=:comment, date=:date, pass=:pass, WHERE id=:id';
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
　　            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
        }else{ ///enumに値がないとき（新規投稿のとき）
            $sql=$pdo->prepare("INSERT INTO board ('id','name','comment','date','pass') VALUES (':id',':name',':comment',':date',':pass')");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
　　        $sql->bindParam(':name', $name, PDO::PARAM_STR);
　　        $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
　　        $sql->bindParam(':date', $date, PDO::PARAM_STR);
　          $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
　　        $sql->execute();
        }
    
    ///データ削除
    if(!empty($_POST["delete"]) && !empty($_POST["pass1"])){
        $delete=$_POST["delete"]; //削除する投稿番号
        $pass1=$_POST["pass1"];
        if($id==$delete && $pass==$pass1){ ///削除対象番号と投稿番号が等しく、パスワードも等しい時
            $sql='delete from board where id=:id';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
            $stmt->execute();
        }else{ ///削除対象番号と投稿番号が等しくないとき、パスワードも等しくないとき
            $sql=$pdo->prepare("INSERT INTO board ('id','name','comment','date','pass1') VALUES (':id',':name',':comment',':date',':pass1')");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
　　        $sql->bindParam(':name', $name, PDO::PARAM_STR);
　　        $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
　　        $sql->bindParam(':date', $date, PDO::PARAM_STR);
　          $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
　　        $sql->execute();
        }
    }
    
    ///データ編集   
    if(!empty($_POST["editnum"]) && !empty($_POST["pass2"])){ ///編集対象番号とパスワードを記入しているとき
        $editnum=$_POST["editnum"]; ///編集対象番号
        $pass2=$_POST["pass2"];
        if($id==$editnum && $pass==$pass2){ ///編集対象番号と投稿番号が等しく、パスワードも等しい時
            $enum=$id ///変更したい投稿番号
            $edit1=$name //変更したい名前
            $edit2=$comment //変更したいコメント
            $sql='UPDATE board SET edit1=:edit1, edit2=:edit2, date=:date, pass2=:pass2, WHERE enum=:enum';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':edit1', $edit1, PDO::PARAM_STR);
            $stmt->bindParam(':edit2', $edit2, PDO::PARAM_STR);
            $stmt->bindParam(':enum', $enum, PDO::PARAM_INT);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
　          $sql->bindParam(':pass2', $pass2, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
            
    ?>
    <form action=""method="post">
        <input type="text" name="name" placeholder="名前入力欄" value="<?php if(isset($edit1)) {echo $edit1;}?>"><br>
        <input type="text" name="comment" placeholder="コメント入力欄" value="<?php if(isset($edit2)) {echo $edit2;}?>"><br>
        <input type="text" name="pass" placeholder="パスワード入力欄"><br>
        <input type="submit" name="submit" value="送信"><br>
        <input type="hidden" name="enum" value="<?php if(isset($enum)) {echo $enum;}?>"><br>    <!--編集している番号（編集モードか判断）-->
    </form>
    <form action=""method="post">
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="pass1" placeholder="パスワード入力欄"><br>
        <input type="submit" name="reset" value="削除"><br>
    </form>   
    <form action=""method="post">
        <input type="number" name="editnum" placeholder="編集対象番号"><br>
        <input type="text" name="pass2" placeholder="パスワード入力欄"><br>
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