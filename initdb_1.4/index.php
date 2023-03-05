<?php

try {
	$db = new mysqli("localhost", "root", "");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:");
}

$db->query("CREATE DATABASE IF NOT EXISTS kgru");

$db->query("USE kgru");

echo "creating table status: id | name | title | color | customCSS | since | until | accesses | icon<br>";
$db->query("DROP TABLE IF EXISTS `status`");

$sql = "CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) DEFAULT NULL,
  `title` varchar(16) DEFAULT NULL,
  `color` varchar(16) DEFAULT '#000000',
  `customCSS` tinytext DEFAULT NULL,
  `since` DATETIME DEFAULT NULL,
  `until` DATETIME DEFAULT NULL,
  `accesses` tinytext DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `levelLink` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";	//TODO: change DB ENGINE / CHARSET if needed

$db->query($sql);

$statuses = array(
	"admin" => array("title" => "Клавомеханик", "accesses" => "full"),
	"org" => array("title" => "Организатор" , "color" => "orange", "accesses" => "view_stat,create_compn,allow_msg"),
	"alt500" => array("title" => "Мастер", "levelLink" => 6),
	"vip" => array("title" => "VIP", "icon" => "data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxMi4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAgLS0+DQo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiIFsNCgk8IUVOVElUWSBuc19mbG93cyAiaHR0cDovL25zLmFkb2JlLmNvbS9GbG93cy8xLjAvIj4NCgk8IUVOVElUWSBuc19zdmcgImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCgk8IUVOVElUWSBuc194bGluayAiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+DQpdPg0KPHN2ZyAgdmVyc2lvbj0iMS4xIiB4bWxucz0iJm5zX3N2ZzsiIHhtbG5zOnhsaW5rPSImbnNfeGxpbms7IiB4bWxuczphPSJodHRwOi8vbnMuYWRvYmUuY29tL0Fkb2JlU1ZHVmlld2VyRXh0ZW5zaW9ucy8zLjAvIg0KCSB3aWR0aD0iMTI5IiBoZWlnaHQ9IjEyNCIgdmlld0JveD0iLTAuNyAtMC4yIDEyOSAxMjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgLTAuNyAtMC4yIDEyOSAxMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGRlZnM+DQo8L2RlZnM+DQo8cG9seWdvbiBvcGFjaXR5PSIwLjU3IiBzdHJva2U9IiMwMDAwMDAiIHBvaW50cz0iNjUuMSw2LjUgODQsNDQuOCAxMjYuMyw1MC45IDk1LjcsODAuNyAxMDIuOSwxMjIuOCA2NS4xLDEwMyAyNy4zLDEyMi44IA0KCTM0LjUsODAuNyAzLjksNTAuOSA0Ni4yLDQ0LjggIi8+DQo8cG9seWdvbiBmaWxsPSIjRkZENDMxIiBzdHJva2U9IiNCRjAwMDAiIHN0cm9rZS13aWR0aD0iMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBwb2ludHM9IjYyLjcsMS41IA0KCTgxLjYsMzkuOCAxMjMuOSw0NS45IDkzLjMsNzUuOCAxMDAuNSwxMTcuOSA2Mi43LDk4IDI0LjksMTE3LjkgMzIuMSw3NS44IDEuNSw0NS45IDQzLjgsMzkuOCAiLz4NCjxnPg0KCTxsaW5lYXJHcmFkaWVudCBpZD0iWE1MSURfMV8iIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iNjIuNjc2OCIgeTE9IjE0LjA1NzYiIHgyPSI2Mi42NzY4IiB5Mj0iMTA1Ljg5NzkiPg0KCQk8c3RvcCAgb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojRkZGRkZGIi8+DQoJCTxzdG9wICBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiNGRkRCMDAiLz4NCgk8L2xpbmVhckdyYWRpZW50Pg0KCTxwYXRoIGZpbGw9InVybCgjWE1MSURfMV8pIiBkPSJNNDcuOCw0NC4zYzAsMC0yNC45LDMuNi0zMy40LDQuOGM2LjIsNiwyNC4xLDIzLjUsMjQuMSwyMy41cy00LjIsMjQuOC01LjcsMzMuMg0KCQljNy42LTQsMjkuOC0xNS43LDI5LjgtMTUuN3MyMi4yLDExLjcsMjkuOCwxNS43Yy0xLjUtOC41LTUuNy0zMy4yLTUuNy0zMy4yczE4LTE3LjUsMjQuMS0yMy41Yy04LjUtMS4yLTMzLjQtNC44LTMzLjQtNC44DQoJCVM2Ni41LDIxLjgsNjIuNywxNC4xQzU4LjksMjEuOCw0Ny44LDQ0LjMsNDcuOCw0NC4zeiIvPg0KPC9nPg0KPC9zdmc+DQo="),
	"personal668817" => array("title" => "Титан", "color" => "#000000", "customCSS" => "font-weight: bold"),
	"foolsday" => array("customCSS" => "transform:rotate(180deg)", "since" => '2023-04-01 00:00:00', "until" => '2023-04-01 23:59:59')
);

$statuses_id = array();

echo "loading \$statuses to table status <br>";

foreach ($statuses as $name=>$data)
{
	$sql = "INSERT INTO `status` (name, title, color, customCSS, since, until, accesses, icon, levelLink) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	if ($stmt) {
		$stmt->bind_param("ssssssssi", $name, $data['title'], $data['color'], $data['customCSS'], $data['since'], $data['until'], $data['accesses'], $data['icon'], $data['levelLink']);
		$stmt->execute();

		$statuses_id[$name] = $db->insert_id;
	} else {
		die($db->error);
	}
}


echo "creating table userstatus: id | user_id | status_id | enabled<br>";

$db->query("DROP TABLE IF EXISTS `userstatus`");

$sql = "CREATE TABLE `userstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT 0,
  `status_id` int(11) DEFAULT 0,
  `enabled` bool DEFAULT true,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";	//TODO: change DB ENGINE / CHARSET if needed

$db->query($sql);

$userstatuses = array(
	"admin" => array(21, 82885, 123190, 474104),
	"org" => array(233444),
	"alt500" => array(217625),
	"vip" => array(73879),
	"personal668817" => array(668817),
	"foolsday" => array(111),
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
