<?php
require 'validation.php';
$errors = validation($_POST);  #エラーメッセージを変数$errorsに保持

$id = uniqid();

$fp = fopen('data.csv', 'a+b');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($errors)){
      fputcsv($fp, [$id,$_POST['title'], $_POST['body']]);
      rewind($fp);
      }
}
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
fclose($fp);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="board.css" rel="stylesheet" type="text/css" media="all">
<title>Laravel News</title>
</head>
<body>
<h1 class="theme"><a href="http://localhost/board.php" style="text-decoration:none;">Laravel News</a></h1>
<section>
<h2 class="share">さぁ、最新のニュースをシェアしましょう</h2>
<?php if(!empty($errors)) : ?>
      <?php echo '<ul>'; ?>
        <?php
          foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
          }
        ?>
      <?php echo '</ul>'; ?>
<?php endif ?>

<form action="" method="post" onSubmit="return checkSubmit()">
      <div>
        <label for="title">タイトル:</label>
        <input type="text"  class="title" name="title">
      </div>
      <div>
        <label for="message">記事:</label>
        <textarea rows="10" cols="60" name="body"></textarea>
      </div>
      <div class="input-submit">
        <input type="submit" name="send" class="btn-submit" value="投稿">
      </div>
    </form>
    <script type="text/javascript">
      function checkSubmit() {
        return confirm("投稿してよろしいですか？");
      }
    </script>
</section>
<section>
<?php if (!empty($rows)): ?>
    <ul>
<?php foreach ($rows as $row): ?>
        <li><?=$row[1]?></li>

        <?php
        $limit = 60;
        $text = $row[2];
        ?>
        <?php if(mb_strlen($text) > $limit): ?>
          <?php $part_text = mb_substr($text,0,$limit); ?>
            <li><?=$part_text. ・・・;?></li>
          <?php else: ?>
            <li><?=$text?></li>
        <?php endif; ?>

        <?php
        if (($handle = fopen("data.csv","r")) !== FALSE) {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            echo <<<eof
            <a href="posts.php?id={$row[0]}">記事全文・コメントを見る</a>
            eof;
            break;
          }
          fclose($handle);
        }
        ?>

        <hr>
<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>投稿はまだありません</p>
<?php endif; ?>
</section>
</body>
</html>