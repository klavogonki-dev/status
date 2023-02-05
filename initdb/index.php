<?php

try {
	$db = new mysqli("localhost", "root", "root");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:root");
}
$db->query("CREATE DATABASE IF NOT EXISTS kgru");


echo "todo: create table status: id | name | title | color <br>";
$statuses = array(
	"admin" => array("title" => "Клавомеханик", "color" => "rang"),
	"org" => array("title" => "Организатор" , "color" => "orange"),
	"personal668817" => array("title" => "Титан", "color" => "#000000")
);
echo "todo: load \$statuses to table status <br>";


echo "todo: create table userstatus: id | user_id | status <br>";
$userstatuses = array(
	"admin" => array(21, 82885, 123190, 474104),
	"personal668817" => array(668817)
);
echo "todo: load \$userstatuses to table userstatus <br>";

?>
