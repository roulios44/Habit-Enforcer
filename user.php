<?php
class User{
    public String $username;
    public String $email;
    public String $password;
    public int $id;
    public int $groupID;
    public bool $hasAddHabit;
    public DateTime $lastConnection;
    function __construct(String $username,String $email,String $password,int $id,int $groupID,bool $hasAddHabit)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
        $this->groupID = $groupID;
        $this->hasAddHabit = $hasAddHabit;
    }
}
?>