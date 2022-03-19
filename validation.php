<?php
  function validation($request) {
    $errors = [];

    if (empty($request['title'])){
      $errors[] = 'タイトルは必須項目です。';
    }  elseif (30 <= mb_strlen($request['title'])){
      $errors[] = 'タイトルは30字以内です。';
    }
    if (empty($request['body'])){
      $errors[] = '記事は必須項目です。';
    }
    return $errors;
  }





?>