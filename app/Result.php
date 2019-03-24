<?php
namespace App;

use PDO;
use PDOException;

class Result
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            die();
        }
    }

    public function getValueByKey($key)
    {

    }

    public function setNewValue($key, $value)
    {
        $exists = $this->pdo->query("SELECT key_ FROM storage WHERE key_ = '$key'");

        if($exists->fetch()) {
            return json_encode(["error" => "Dublicate key: ".$key]);
        }

        $res = $this->pdo->prepare("INSERT INTO storage (key_, value_) VALUES ('$key', '$value')");

        if ($res->execute()) {
            return json_encode(["result" => "success"]);
        } else {
            http_response_code(400);
            return json_encode(["error" => "error"]);
        }
    }

    public function deleteValueByKey($key)
    {
        //
    }
}