<?php
namespace App;

use PDO;
use PDOException;

class Result
{
    private $pdo;
    private $errors = [];
    private $success = [];

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
            $res = $this->pdo->query("SELECT count(id) as count FROM storage");
            if (intval($res->fetch(PDO::FETCH_ASSOC)['count']) > 1024) {
                $this->errors[] = "storage is full";
                return $this->response();
                die();
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            die();
        }
    }

    public function getValueByKey($key)
    {
        $exists = $this->pdo->query("SELECT value_ FROM storage WHERE key_ = '$key' LIMIT 1");
        $result = $exists->fetch(PDO::FETCH_ASSOC);

        if(!$result) {
            $this->errors[] =  "key not exists: ".$key;
        } else {
            $this->success = ["value" => $result['value_']];
        }

        return $this->response();
    }

    public function setNewValue($key, $value)
    {
        $exists = $this->pdo->query("SELECT key_ FROM storage WHERE key_ = '$key'");
        if($exists->fetch()) {
            $this->errors[] = "dublicate key: ".$key;
        }

        if (strlen($key)  >= 16) {
            $this->errors[] = "key too long";
        }

        $res = $this->pdo->prepare("INSERT INTO storage (key_, value_) VALUES ('$key', '$value')");
        if ($res->execute()) {
            $this->success = ["status" => "success"];
        } else {
            $this->errors[] = "problems with database connection";
        }
        return $this->response();
    }

    public function deleteValueByKey($key)
    {
        $exists = $this->pdo->query("SELECT key_ FROM storage WHERE key_ = '$key'");
        if(!$exists->fetch()) {
            $this->errors[] =  "key not exists: ".$key;
        }

        $res = $this->pdo->prepare("DELETE FROM storage WHERE key_ = '$key'");


        if ($res->execute()) {
            $this->success = ["status" => "success"];
        } else {
            $this->errors[] = "problems with database connection";
        }

        return $this->response();
    }

    public function response()
    {
        if (count($this->errors) > 0) {
            http_response_code(400);
            return json_encode(["error" => $this->errors[0]]);
        } else {
            return json_encode($this->success);
        }
    }
}