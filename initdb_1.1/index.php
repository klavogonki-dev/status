<?php

try {
	$db = new mysqli("localhost", "root", "");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:");
}

$db->query("CREATE DATABASE IF NOT EXISTS kgru");

$db->query("USE kgru");

echo "creating table status: id | name | title | color <br>";
$db->query("DROP TABLE IF EXISTS `status`");

$sql = "CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `title` varchar(16) DEFAULT NULL,
  `color` varchar(16) DEFAULT '#000000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";	//TODO: change DB ENGINE / CHARSET if needed

$db->query($sql);

$statuses = array(
	"admin" => array("title" => "Клавомеханик", "color" => ""),
	"org" => array("title" => "Организатор" , "color" => "orange"),
	"personal668817" => array("title" => "Титан", "color" => "#000000")
);

$statuses_id = array();

echo "loading \$statuses to table status <br>";

foreach ($statuses as $name=>$data)
{
	$sql = "INSERT INTO `status` (name, title, color) VALUES (?, ?, ?)";
	$stmt = $db->prepare($sql);
	if ($stmt) {
		$stmt->bind_param("sss", $name, $data['title'], $data['color']);
		$stmt->execute();

		$statuses_id[$name] = $db->insert_id;
	} else {
		die($db->error);
	}
}


echo "creating table userstatus: id | user_id | status_id <br>";

$db->query("DROP TABLE IF EXISTS `userstatus`");

$sql = "CREATE TABLE `userstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT 0,
  `status_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";	//TODO: change DB ENGINE / CHARSET if needed

$db->query($sql);

$userstatuses = array(
	"admin" => array(21, 82885, 123190, 474104),
	"org" => array(233444, 261337, 405687, 572711),
	"personal668817" => array(668817)
);
echo "loading \$userstatuses to table userstatus <br>";

foreach ($userstatuses as $name=>$data)
{
	$sql = "INSERT INTO `userstatus` (user_id, status_id) VALUES (?, ?)";

	foreach ($data as $user_id)
	{
		$stmt = $db->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("dd", $user_id, $statuses_id[$name]);
			$stmt->execute();
		} else {
			die($db->error);
		}
	}
}

echo "all done, exiting... <br>";

?>
