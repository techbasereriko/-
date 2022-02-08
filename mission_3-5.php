<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_3-5</title>
</head>
<body>
    <h1>みんなの出身県は？</h1>
    <?php
    $filename="mission_3-5.txt";
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){ ///名前とコメントとパスワードを記入しているとき
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass"];
        
        if(file_exists($filename)){
            $line=file($filename, FILE_IGNORE_NEW_LINES);
            $last=count($line)-1; ///配列番号（0から始まる）
            $lines=explode("<>", $line[$last]);
            $num=$lines[0]+1; ///投稿番号（ファイルが存在するときはひとつ前の投稿番号＋1）
        }else{
            $num=1; ///ファイルが存在しない時の投稿暗号は１
        }
        $newdata=$num."<>".$name."<>".$comment."<>".$date."<>".$pass;
        $fp=fopen($filename, "a");
        if(!empty($_POST["enum"])){ ///enumに値があるとき（編集モードで）
            $edit=$_POST["enum"]; ///編集番号
            $fp=fopen($filename, "w+"); ///もともと入っていたデータは消す
            foreach($line as $line){
                $line1=explode("<>", $line);
                if($line1[0]==$edit){ ///投稿番号が編集番号と等しい時
                    fwrite($fp, $edit."<>".$name."<>".$comment."<>".$date."<>".$pass.PHP_EOL);
                }else{ ///投稿番号が編集番号と等しくないとき
                    fwrite($fp, $line.PHP_EOL);
                }
            }
        }else{ ///enumに値がない時
                fwrite($fp, $newdata.PHP_EOL);
            fclose($fp);
        }
    }
   
    if(!empty($_POST["delete"]) && !empty($_POST["pass1"])){
        $delete=$_POST["delete"];
        $pass1=$_POST["pass1"];
        $str=file($filename, FILE_IGNORE_NEW_LINES);
        $fp=fopen($filename, "w+");
        foreach($str as $str){
            $str1=explode("<>", $str);
                if($str1[0]==$delete && $str1[4]==$pass1){ ///投稿番号が削除対象番号と等しいとき、パスワードも等しいとき
                }else{ ///投稿番号が削除対象番号と等しくないとき、パスワードが等しくないとき
                    fwrite($fp, $str.PHP_EOL);
                }
        }
        fclose($fp);
    }
   
    if(!empty($_POST["editnum"]) && !empty($_POST["pass2"])){ ///編集対象番号とパスワードを記入しているとき
        $editnum=$_POST["editnum"]; ///編集対象番号
        $pass2=$_POST["pass2"];
        
        $box=file($filename, FILE_IGNORE_NEW_LINES);
        $fp=fopen($filename, "a");
        foreach($box as $box){
            $box1=explode("<>", $box);
            if($box1[0]==$editnum && $box1[4]==$pass2){ ///投稿番号が編集対象番号と等しく、またパスワードが正しいとき
               $enum=$box1[0]; ///編集する投稿番号を取得
               $edit1=$box1[1]; ///編集する名前を取得
               $edit2=$box1[2]; ///編集するコメントを取得
            }
        }
        fclose($fp);
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
    if(file_exists($filename)){
        $box=file($filename, FILE_IGNORE_NEW_LINES);
        foreach($box as $box){
            $box1=explode("<>", $box);
            echo $box1[0].". ".$box1[1]."  [".$box1[2]."] ".$box1[3]."<br>";
        }
    }
    ?>
</body>
</html>