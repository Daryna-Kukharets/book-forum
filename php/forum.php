<?php
if (!defined('SEC')) { die('Немає доступу'); }

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'add_topic' && $GLOBALS['currentUser']) {
    if (isset($GLOBALS['currentUser']['id'])) {
        $bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : 0;
        $result = mysqli_query($db, "
            INSERT INTO topic (name, countMessages, userId, dateCreate, bookId)
            VALUES (
                '" . $db->real_escape_string($_REQUEST['name']) . "', 
                '1', 
                '" . $db->real_escape_string($GLOBALS['currentUser']['id']) . "', 
                '" . time() . "', 
                '" . $bookId . "'
            );
        ");

        $topicId = $db->insert_id;

        $result = mysqli_query($db, "
            INSERT INTO post (topicId, userId, message, dateCreate)
            VALUES (
                '" . $topicId . "', 
                '" . $db->real_escape_string($GLOBALS['currentUser']['id']) . "', 
                '" . $db->real_escape_string($_REQUEST['message']) . "', 
                '" . time() . "'
            );
        ");

        // Побудова нової адреси з додаванням ID книги
        $redirectUrl = '/book-forum/php/?page=forum&forumId=' . $bookId;
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        die('Користувач не авторизований або не має ID');
    }
}

// Отримання ID книги з URL
$bookId = isset($_GET['forumId']) ? intval($_GET['forumId']) : 0;

// SQL-запит для отримання тем, які стосуються цієї книги
$sql = "SELECT * FROM topic WHERE bookId = " . $bookId;
$result = $db->query($sql);

$topicList = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $topicList[] = $row;
    }
}

$sql = "SELECT * FROM user";
$result = $db->query($sql);

$userList = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userList[$row['id']] = $row;
    }
}
?>

<?php include PATH.'/header.php'?>

<h1 class="title">Теми</h1>
<table class="table">
  <thead class="thead">
    <tr class="tr">
      <th class="th">Назва теми</th>
      <th class="th">Кількість повідомлень</th>
      <th class="th">Автор</th>
      <th class="th">Дата створення</th>
      <th class="th">Остання відповідь</th>
      <th class="th">Дата відповіді</th>
    </tr>
  </thead>
  <tbody class="tbody">
    <?php if (empty($topicList)): ?>
      <tr class="tr">
        <td class="td" colspan="6">Немає тем для цієї книги.</td>
      </tr>
    <?php else: ?>
      <?php foreach($topicList as $topic): ?>
    <tr class="tr tr-forum" onclick="window.location.href='/book-forum/php/?page=topic&topicId=<?=$topic['id']?>&bookId=<?=$bookId?>'">
      <td class="td"><?=$topic['name']?></td>
      <td class="td"><?=$topic['countMessages']?></td>
      <td class="td"><?=$userList[$topic['userId']]['login']?></td>
      <td class="td"><?=date('d-m-Y H:i:s', $topic['dateCreate'])?></td>
      <td class="td"><?= isset($topic['replyUserId']) && isset($userList[$topic['replyUserId']]) ? $userList[$topic['replyUserId']]['login'] : 'Немає відповіді' ?></td>
      <td class="td"><?= $topic['dateReply'] ? date('d-m-Y H:i:s', $topic['dateReply']) : '---' ?></td>
    </tr>
<?php endforeach ?>
    <?php endif ?>
  </tbody>
</table>

<?php if ($GLOBALS['currentUser']):?>
  <table class="reply_form table">
    <thead class="thead">
      <tr class="tr">
        <th class="th-th">Створити нову тему</th>
      </tr>
    </thead>
    <tbody class="tbody">
      <tr class="tr-tr">
        <td class="td-td">
          <form class="message_form" action="/book-forum/php/?action=add_topic&forumId=<?=$bookId?>" method="post">
            <input class="input-forum" type="text" name="name" placeholder="Назва теми" required>
            <textarea name="message" placeholder="Напишіть повідомлення"></textarea>
            <button type="submit" name="create">Створити</button>
          </form>
        </td>
      </tr>
    </tbody>
  </table>
<?php endif?>
<?php include PATH.'/footer.php'?>