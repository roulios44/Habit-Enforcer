<?php

function openDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    try {
        $con = mysqli_connect($servername, $username, $password, "habit");
    } catch (Exception $e){
        echo $e;
        die("couldn't connect to DB");
    }
    return $con;
}

function addUserDB(String $username, String $email, String $pwd) {
    $con = openDB();
    $stmt = $con->prepare("INSERT INTO user (username, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $email, $pwd);
    $stmt->execute();
}