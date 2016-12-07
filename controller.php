<?php
require_once './IMDB_DataBase.php';

$movie = $_GET['movie'];
$first = $_GET['firstname'];
$last = $_GET['lastname'];

$array = $DB->getMatchingMovies($movie, $first, $last);
echo json_encode($array);

?>