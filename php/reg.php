<title>Реєстрація</title>
<?php
if(!defined('SEC')) { die('Немає доступу');}

if (!defined('PROJECT')) {
  define('PROJECT', 'book-forum');
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'reg' && (!isset($_SESSION['login']) || !$_SESSION['login'])) {
  $login = $db->real_escape_string($_REQUEST['login']);
  $email = $db->real_escape_string($_REQUEST['email']);

  $sql = "SELECT * FROM User WHERE login = '$login' or email = '$email'";
  $result = $db->query($sql);

  $user = [];

  if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $user[] = $row;
      
      if($row['login'] === $_REQUEST['login']) {
        header('location: /book-forum/php/?page=reg&message=login_exists&login='.$_REQUEST['login'].'&forumId='.$bookId);
        exit;
      }

      if($row['email'] === $_REQUEST['email']) {
        header('location: /book-forum/php/?page=reg&message=email_exists&email='.$_REQUEST['email'].'&forumId='.$bookId);
        exit;
      } 
    } 
  }

  
  $bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : (isset($_GET['bookId']) ? intval($_GET['bookId']) : 0);

  $_REQUEST['password'] = password_hash($_REQUEST['password'], PASSWORD_BCRYPT, ['cost' => 12]);

  $result = mysqli_query($db, "
    INSERT INTO User (email,login, password, name, surname)
    VALUES ('{$_REQUEST['email']}', '{$_REQUEST['login']}', '{$_REQUEST['password']}', '{$_REQUEST['name']}', '{$_REQUEST['surname']}');
  ");
  
  
  header('location: /book-forum/php/?page=auth&message=reg_success&forumId=' . $bookId);
  exit;
}
?>


<?php include PATH.'/header.php'?>

<h1 class="title">Реєстрація</h1>


<?php if (!isset($_SESSION['login']) || !$_SESSION['login']): ?>
  <form class="form" action="/book-forum/php/?page=reg&action=reg&forumId=<?=$bookId?>" method="post">
    <input type="email" name="email" value="" placeholder="Введіть E-mail" required><br>
    <input type="text" name="login" value="" placeholder="Введіть логін" required><br>
    <input type="password" name="password" value="" placeholder="Введіть пароль" required minlength="4"><br>
    <input type="text" name="name" value="" placeholder="Введіть ім'я" required><br>
    <input type="text" name="surname" value="" placeholder="Введіть фамілію" required><br>

    <button type="submit" value="Відправити">Відправити</button>
    <a class="reg-auth" href="./?page=auth&forumId=<?=$bookId?>">Вже зареєстровані?</a>
  </form>
<?php else:?>
  <div>Ви авторизовані, <a href="./?action=logout">Вихід</a></div>
<?php endif?>

<?php include PATH.'/footer.php'?>
