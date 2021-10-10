<?php

$servname = 'localhost';
$uname    = 'root';
$dbpass   = '';
$dbname   = 'cms';

$conn = mysqli_connect($servname,$uname,$dbpass,$dbname);

if($conn){
    echo "connection to Mysql DB success";
}else{
    die('Error : '.mysqli_connect_error());
}


?>