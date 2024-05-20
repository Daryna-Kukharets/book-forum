<?php
if(!defined('SEC')) { die('Немає доступу');}

$systemMessage = '';

if (isset($_REQUEST['message'])) {
  switch ($_REQUEST['message']) {
    case 'reg_success':
      $systemMessage = 'Реєстрація пройшла успішно!';
      break;

    case 'logout_success':
      $systemMessage = 'Ви вийшли із системи!';
      break;

    case 'auth_deny':
      $systemMessage = 'Не правильний логін або пароль!';
      break;

    case 'login_exists':
      $systemMessage = 'Логін "'.$_REQUEST['login'].'" вже існує';
      break;

    case 'email_exists':
      $systemMessage = 'E-mail "'.$_REQUEST['email'].'" вже існує';
      break;
  }
}
$bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : (isset($_GET['bookId']) ? intval($_GET['bookId']) : 0);

?>


<!DOCTYPE html>
<html lang="ua" class="page">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Форум</title>
  <link
      rel="icon"
      type="images/x-icon"
      href="../images/icons/icon.png"
  >
  <link rel="stylesheet" href="../php-css/header.css">
  
  <style>
    .table {
      border-collapse: collapse;
      margin-top: 50px;
      width: 100%;
      border-radius: 8px 8px 0 0;
      overflow: hidden;
      box-shadow: 20px 20px 20px rgba(0, 0, 0, 0.2);
    }



    thead .tr {
      background-color: #04000acd;
      color: #fff;
      text-align: left;
    }

    .th, 
    .td,
    .td-td,
    .th-th {
      padding: 12px 15px;
    }

    .td:not(:last-child) {
      border-right: 1px solid #ffffff48;
    }

    tbody .tr:nth-last-of-type(even) {
      background-color: rgba(11, 0, 31, 0.398);
    }

    tbody .tr:nth-last-of-type(even) {
      background-color: rgba(11, 0, 31, 0.398);
    }

    tbody .tr {
      border-bottom: 1px solid #ffffffc0;
      color: #fff;
      font-weight: 400;
    }

    tbody .tr-forum:hover {
      cursor: pointer;
      background-color: #b05fe675;
      transition: all 0.3s;
    }


    tbody .tr:last-of-type {
    border-bottom: 3px solid #fff ;
    }

    .input-forum {
      background-color: transparent;
      width: 90%;
    }
    
    textarea {
      background-color: transparent;
      font-size: 14px;
      width: 90%;
      height: 100px;
      border: none;
      border-bottom: 1px solid #565656df;
      resize: none;
      margin-top: 20px;
      color: #fff;
      outline: none;
    }

    textarea:focus {
      border-bottom: 1px solid #fff;
    }

    .reply_form {
      margin-top: 20px; 
      margin-bottom: 60px;
      background-color: rgba(11, 0, 31, 0.398);
    }

    .choice {
      border-bottom: none;
      padding: 10px 20px;
    }

    .choice:hover {
      color: #9f64eb;
      cursor: pointer;
    }

    .messages tbody tr td:first-child {
      width: 55%;
    }

    .messages tbody tr td:first-child a {
      display: inline-block;
    }

    .messages tbody tr td:first-child span {
      display: block; 
      margin: 10px 0;
    }

    a {
      color: #fff;
      transition: all 0.3s;
    }

    a:hover {
      color: #9f64eb;
    }

    button {
      display: block;
    }

    .header-block {
      margin-top: 20px;
      display: flex;
      gap: 60px;
    }

    .a-header {
      text-decoration: none;
      font-weight: 700;
      font-size: 18px;
    }
  </style>
  
</head>
<body class="page__body">
  <div class="wrapper">
    <div class="sidebar_left"></div>
    <div class="content">

      <div class="header-block">
        <a class="a-header" href="../bookCard.html?id=<?= $bookId ?>">Повернутись до опису книги</a>
        <?php if (!isset($_SESSION['login']) || !$_SESSION['login']): ?>
          <a class="a-header" href="./?page=auth&forumId=<?=$bookId?>">Авторизація</a>
          <a class="a-header" href="./?page=reg&forumId=<?=$bookId?>">Реєстрація</a>
          <?php else:?>
            <a class="a-header" href="./?page=forum&action=logout&forumId=<?=$bookId?>">Вихід (<?=$_SESSION['login']?>)</a>
        <?php endif?>
      </div>

      <?php if($systemMessage):?>
        <div class="alert">
          <b>Системне сповіщення:</b><br>
          <?=$systemMessage?>
        </div>
      <?php endif?>
      <hr>



