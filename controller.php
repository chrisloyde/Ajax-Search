<?php
require_once './IMDB_DataBase.php';

if (isset($_GET['movie'])) {
    $sub = $_GET['movie'];
    $array = $DB->searchMovie($sub);
    echo json_encode($array);
}

if (isset($_GET['firstname']) && isset($_GET['lastname'])) {
    $last = $_GET['lastname'];
    $first = $_GET['firstname'];
    $array = $DB->searchActor($first, $last);
    echo json_encode($array);
}
else if (isset($_GET['firstname']) || isset($_GET['lastname'])) {
    if (isset($_GET['firstname'])) {
        $first = $_GET['firstname'];
        $array = $DB->searchActor($first, "");
        echo json_encode($array);
    } else {
        $last = $_GET['lastname'];
        $array = $DB->searchActor($last, "");
        echo json_encode($array);
    }
}
?>