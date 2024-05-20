<?php
if (!defined('SEC')) { die('Немає доступу'); }


$bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : (isset($_GET['bookId']) ? intval($_GET['bookId']) : 0);

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'auth' && (!isset($_SESSION['login']) || !$_SESSION['login'])) {
  $login = $db->real_escape_string($_REQUEST['login']);

  $sql = "SELECT * FROM User WHERE login = '$login'";
  $result = $db->query($sql);

  $user = [];

  if ($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
      $user[] = $row;

      if(password_verify($_REQUEST['password'], $row['password'])) {
        $_SESSION['login'] = $row['login'];
        $_SESSION['password'] = $row['password'];
        header('Location: /book-forum/php?page=forum&forumId=' . $bookId);
        exit;
      } else {
        $_SESSION['login'] = '';
        $_SESSION['password'] = '';
        header('Location: /book-forum/php/?page=auth&message=auth_deny&forumId=' . $bookId);
        exit;
      }
      
    } 
  }
}
?>

<?php include PATH.'/header.php'?>

<h1 class="title">Авторизація</h1>
<?php if (!isset($_SESSION['login']) || !$_SESSION['login']): ?>
  <form class="form" action="/book-forum/php/?page=auth&action=auth&forumId=<?=$bookId?>" method="post">
    <input type="text" name="login" value="" placeholder="Введіть логін" required><br>
    <input type="password" name="password" value="" placeholder="Введіть пароль" required><br>
    
    <button type="submit" value="Увійти">Увійти</button>
    <a class="reg-auth" href="./?page=reg&forumId=<?=$bookId?>">Все ще не зареєстровані?</a>
</form>
<?php else:?>
  <div>Ви авторизовані,  <a href="./?action=logout">Вихід</a></div>
<?php endif?>

<?php include PATH.'/footer.php'?>