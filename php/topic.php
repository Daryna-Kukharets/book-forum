<?php
  if(!defined('SEC')) { 
    die('Немає доступу');
  }

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'add_post' && $GLOBALS['currentUser']) {
  if (isset($GLOBALS['currentUser']['id'])) {
      $result = mysqli_query($db, "
          INSERT INTO post (topicId, userId, message, dateCreate)
          VALUES ('{$_REQUEST['topicId']}', '{$GLOBALS['currentUser']['id']}', '{$_REQUEST['message']}', '".time()."');
      ");

      $result = mysqli_query($db, "
        UPDATE topic
          SET countMessages = countMessages + 1,
            dateReply = '".time()."',
            replyUserId = '{$GLOBALS['currentUser']['id']}'
          WHERE id = '{$_REQUEST['topicId']}';
      ");

      header('Location: /book-forum/php/?page=topic&topicId='.$_REQUEST['topicId']);
      exit;
  } else {
      die('Користувач не авторизований або не має ID');
  }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'edit_post' && $GLOBALS['currentUser']) {
  if($_REQUEST['editSave']) {
    $result = mysqli_query($db, "
      UPDATE post
        SET message = '{$_REQUEST['message']}'
        WHERE id = '{$_REQUEST['postId']}';
    ");
  }

      header('Location: /book-forum/php/?page=topic&topicId='.$_REQUEST['topicId']);
      exit;
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'delete_post' && $GLOBALS['currentUser']) {
    $result = mysqli_query($db, "DELETE FROM post WHERE id = '{$_REQUEST['postId']}'");

    $result = mysqli_query($db, "
      UPDATE topic
        SET countMessages = countMessages - 1
        WHERE id = '{$_REQUEST['topicId']}';
    ");
      header('Location: /book-forum/php/?page=topic&topicId='.$_REQUEST['topicId']);
      exit;
}

$sql = "SELECT * FROM post WHERE topicId = '{$_REQUEST['topicId']}'";
$result = $db->query($sql);

$postList = [];

if($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $postList[] = $row;
  
  } 
}

$bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : (isset($_GET['bookId']) ? intval($_GET['bookId']) : 0);

$sql = "SELECT name FROM topic WHERE id = '{$_REQUEST['topicId']}'";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $topicName = $result->fetch_assoc()['name'];
} else {
    $topicName = 'Невідома тема'; // Якщо тему не знайдено
}

$sql = "SELECT * FROM user";
$result = $db->query($sql);

$userList = [];

if($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $userList[$row['id']] = $row;
  
  } 
}
?>

<?php include PATH.'/header.php'?>


<h1 class="title"><a href="/book-forum/php/?page=forum&forumId=<?=$bookId?>">Форум</a> -<?= $topicName ?></h1>
<table class="messages table">
  <thead class="thead">
    <tr class="tr">
      <th class="th">Автор</th>
      <th class="th">Повідомлення</th>
    </tr>
  </thead>
  <tbody class="tbody">
    <?php foreach($postList as $post):?>
      <tr class="tr">
        <td class="td">
          <p><?=$userList[$post['userId']]['login']?></p>
          <span><?=date('d-m-Y H:i:s', $post['dateCreate'])?></span>
          <?php if($GLOBALS['currentUser'] && $GLOBALS['currentUser']['id'] === $post['userId']):?>
            <a href="/book-forum/php/?page=topic&topicId=<?=$_REQUEST['topicId']?>&action=delete_post&postId=<?=$post['id']?>">Видалити</a>
            <a href="/book-forum/php/?page=topic&topicId=<?=$_REQUEST['topicId']?>&mode=edit&postId=<?=$post['id']?>">Змінити</a>
          <?php endif?>
          </td>
        <td class="td">
        <?php if(isset($GLOBALS['currentUser'], $_REQUEST['mode']) && $_REQUEST['mode'] === 'edit' && (int)$post['id'] === (int)$_REQUEST['postId']): ?>
            <form class="message_form" action="/book-forum/php/?page=topic&topicId=<?=$_REQUEST['topicId']?>&action=edit_post&postId=<?=$post['id']?>" method="post">
              <input class="input-forum" type="hidden" name="postId" value="<?=$post['id']?>">
              <textarea name="message"><?=$post['message']?></textarea>
              <input class="choice" type="submit" name="editSave" value="Зберегти зміни">
              <input class="choice" type="submit" value="Відміна">
            </form>
            <?php else:?>
              <?=$post['message']?>
            <?php endif?>
        </td>
      </tr>
    <?php endforeach?>
  </tbody>
</table>

<?php if($GLOBALS['currentUser']):?>
<table class="reply_form table">
  <thead class="thead">
    <tr class="tr">
      <th class="th">Відповісти в тему</th>
    </tr>
  </thead>
  <tbody class="tbody">
    <tr class="tr">
      <td class="td">
        <form class="message_form" action="/book-forum/php/?page=topic&action=add_post" method="post">
          <input type="hidden" name="topicId" value="<?=$_REQUEST['topicId']?>">
          <textarea name="message" placeholder="Напишіть відповідь..." required></textarea>
          <input class="choice" type="submit" name="reply" value="Відповісти">
        </form>
      </td>
    </tr>
  </tbody>
</table>
<?php endif?>

<?php include PATH.'/footer.php'?>