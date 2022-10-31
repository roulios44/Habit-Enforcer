<?php
class User
{
    public $ID;
    public $username;
    private $mail;
    private $password;
    private $habit;

    public function __construct(int $ID, string $username, string $mail,string $password) {
        $this->ID = $ID;
        $this->username = $username;
        $this->mail = $mail;
        $this->password = $password;
        $this->habit = null;
    }

}

class Habit
{
    private $value;
    private $difficulty;
    private $color;
    private $period;
}