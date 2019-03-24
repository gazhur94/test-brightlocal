<?php

require __DIR__.'/vendor/autoload.php';

$result = new App\Result;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $result->getValueByKey($_GET['key']);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo $result->setNewValue($_POST['key'], $_POST['value']);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    echo $result->deleteValueByKey($_POST['key']);
} else {
    echo json_encode(["error" => "not found"]);
}


