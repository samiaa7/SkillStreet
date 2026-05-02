<?php
$host     = "localhost";
$dbname   = "skillstreet";
$user     = "postgres";
$password = "newpassword123";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database connection failed.");
}

session_start();
?>