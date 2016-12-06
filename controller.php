<?php
require_once './IMDB_DataBase.php';
$sub = $_GET['substring'];
$array = $DB->search($sub);
echo json_encode($array);
?>