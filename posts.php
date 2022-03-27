<?php
require 'validation_posts.php';
$errors = validation($_POST);  #エラーメッセージを変数$errorsに保持


  if (empty($rows)) {
    $rows　= [];
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete = $_POST['delete'];
    $comment = $_POST['comment'];

    if ($delete && strlen($delete) > 0) {
      $lines = file("comment.txt", FILE_IGNORE_NEW_LINES);
      $fp = fopen('comment.txt', 'w');

      for($i = 0; $i < count($lines); $i++){
        $line = explode(",",$lines[$i]);
        $postnum = $line[0];

        if($postnum != $delete){
          fwrite($fp, $lines[$i]."\n");
        }
      }
      // 再度読み込み専用で開く。削除後に書き換えることはないので
      $fp = fopen('comment.txt', 'r');

    } else if ($_GET["id"]) {

      $fp = fopen('comment.txt', 'a+b');
      $id = uniqid();
      if (empty($errors)){
        fputcsv($fp, [
          ($id),
          ($_GET["id"]),
          ($_POST['comment'])
        ]);
      }

      rewind($fp);
    } else {
      $fp = fopen('comment.txt', 'r');
    }

    while ($row = fgetcsv($fp)) {
      if (count($row) > 0) {
        $rows[] = $row;
      }
    }
     //var_dump(count($rows[0]));
    fclose($fp);
  } else {
    $fp = fopen('comment.txt', 'r');
    while ($row = fgetcsv($fp)) {
      if (count($row) > 0) {
        $rows[] = $row;
      }
    }
    // var_dump(count($rows[0]));
    fclose($fp);
  }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link href="posts.css" rel="stylesheet" type="text/css" media="all">
  <title>Laravel News</title>
</head>
<body>
<h1 class="theme"><a href="http://localhost/board.php" style="text-decoration:none;">Laravel News</a></h1>
<section>
<?php if(!empty($errors)) : ?>
      <?php echo '<ul>'; ?>
        <?php
          foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
          }
        ?>
      <?php echo '</ul>'; ?>
<?php endif ?>

<?php
$post_id = $_GET["id"];
//csvデータを読み込み、idが一致したらデータを書き出し終了する
//csvにはid、タイトル、記事の順番で入っている
if (($handle = fopen("data.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
  if($post_id == $data[0]) {

  echo <<<eof
    {$data[1]}<br /><br />
    {$data[2]}<br /><br />
  eof;
  break;
  }
}
  fclose($handle);
}

?>
<hr>

<form action="" method="post">
  <div>
  <textarea class="comment" name="comment"></textarea>
  </div>
  <div>
    <input type="submit" name="send" class="btn-comment" value="コメントを書く">
  </div>
</form>


<?php if (!empty($rows)): ?>
  <ul>
    <?php foreach ($rows as $row): ?>
      <?php $child_id = $row[1]; ?>
      <!-- ここで該当のデータを取得しているぽい -->
      <?php if ($post_id == $child_id): ?>
        <div class="post">
          <li><?=$row[2]?></li>
          <form action="posts.php?id=<?= $post_id ?>" method="post">
            <input type="hidden" name="delete" value=<?= $row[0] ?>>
            <input type="submit" name="submit" class="btn-submit" value="コメントを削除する">
          </form>
        </div>
      <?php endif ?>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>投稿はまだありません</p>
<?php endif; ?>

</section>
</body>
</html>