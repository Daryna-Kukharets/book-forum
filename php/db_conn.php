<?php
$db = new mysqli("localhost", "root", "", "forum");
if ($db->connect_error) {
  die('Підключення не вдалось: '.$db->connect_error);
}
?>