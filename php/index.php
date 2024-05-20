<?php
function d($array) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

// безпека від відкриття файлу напряму
define('SEC', true);

// шлях до проекту
define('PATH', __DIR__);

// назва папки проекту
$path = explode('\\', PATH);
define('PROJECT', end($path));


include PATH.'/db_conn.php';

session_start();

$GLOBALS['currentUser'] = [];

if(isset($_SESSION['login'])) {
  $login = $db->real_escape_string($_SESSION['login']); // Використання $_SESSION для доступу до 'login'
  $sql = "SELECT * FROM User WHERE login = '$login'";
  $result = $db->query($sql);

  if($result->num_rows > 0) {
      if($row = $result->fetch_assoc()) {
          $GLOBALS['currentUser'] = $row;
      } 
  }
} 

$bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : (isset($_GET['bookId']) ? intval($_GET['bookId']) : 0);


if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout' && $_SESSION['login']) {
  unset($_SESSION['login']);
  unset($_SESSION['password']);

  header('Location: /book-forum/php/?page=forum&message=logout_success&forumId=' . $bookId);
  exit;
}

if (!defined('PATH')) {
  define('PATH', __DIR__);
}

// форум
if (!isset($_REQUEST['page'])) {
  include PATH . '/forum.php';
  exit;
}

// Тема
if (!isset($_REQUEST['page']) || $_REQUEST['page'] === 'topic') {
  include PATH . '/topic.php';
  exit;
}

// Реєстрація
if($_REQUEST['page'] === 'reg') {
  include PATH.'/reg.php';
  exit;
}

// Авторизація
if($_REQUEST['page'] === 'auth') {
  include PATH.'/auth.php';
  exit;
}

if($_REQUEST['page'] === 'forum') {
  include PATH.'/forum.php';
  exit;
}

echo 'Помилка 404 - Сторінка не знайдена, <a href="./">Повернутись на головну </a>';
?> 