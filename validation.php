<?php
  function validation($request) {
    $errors = [];

    if (isset($_POST['title']) && empty($request['title'])){
      $errors[] = 'タイトルは必須項目です。';
    }  elseif (30 <= mb_strlen($request['title'])){
      $errors[] = 'タイトルは30字以内です。';
    }
    if (isset($_POST['body']) && empty($request['body'])){
      $errors[] = '記事は必須項目です。';
    }
    return $errors;
  }

?>