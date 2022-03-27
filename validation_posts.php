<?php
  function validation($request) {
    $errors = [];

    if (isset($_POST['comment']) && empty($request['comment'])){
      $errors[] = 'コメントは必須項目です。';
    }  elseif (50 <= mb_strlen($request['comment'])){
      $errors[] = 'コメントは30字以内です。';
    }
    return $errors;
  }

?>