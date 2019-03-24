<?php

class ResultTest extends \PHPUnit_Framework_TestCase
{
    protected $result;
    protected $newKey = 'some_new_key';
    protected $newValue = 'some_new_value';

    public function setUp()
    {
        $this->result = new \App\Result;
    }

    /** @test */
    public function that_return_error_about_non_existent_key_in_get()
    {
        $key = 'some_non_existend_key';
        $this->assertEquals($this->result->getValueByKey($key), json_encode(["error" => "key not exists: ".$key]));
    }

    /** @test */
    public function that_return_error_about_non_existent_key_in_delete()
    {
        $key = 'some_non_existend_key';
        $this->assertEquals($this->result->deleteValueByKey($key), json_encode(["error" => "key not exists: ".$key]));
    }

    /** @test */
    public function that_return_error_about_long_key_in_set_new_value()
    {
        $key = 'my_very_long_key_10000000';
        $value = 'my_value_for_long_key';
        $this->assertEquals($this->result->setNewValue($key, $value), json_encode(["error" => "key too long"]));
    }

    /** @test */
    public function set_new_value()
    {
        $this->assertEquals($this->result->setNewValue($this->newKey, $this->newValue), json_encode(["status" => "success"]));
    }

    /** @test */
    public function get_new_value()
    {
        $this->assertEquals($this->result->getValueByKey($this->newKey), json_encode(["value" => $this->newValue]));
    }

    /** @test */
    public function delete_new_value()
    {
        $this->assertEquals($this->result->deleteValueByKey($this->newKey), json_encode(["status" => "success"]));
    }


}


