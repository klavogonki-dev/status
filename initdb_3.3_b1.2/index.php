<?php

try {
    $db = new mysqli('localhost', 'root', '');
} catch (mysqli_sql_exception $e) {
    die('Need mysql on localhost user:root, pass:');
}

if ($db->connect_error) {
    error_log('DB connection error: ' . $db->connect_error);
    die('DB connection error. See log for details.');
}

$db->query('CREATE DATABASE IF NOT EXISTS kgru');
$db->query('USE kgru');
$db->query('DROP TABLE IF EXISTS `userstatus`');
$db->query('DROP TABLE IF EXISTS `status`');

echo 'creating table status: id | name | title | color | customCSS | accesses | icon<br>';

$sql = "CREATE TABLE `status` (
  `id` int(11) KEY AUTO_INCREMENT,
  `name` varchar(16) UNIQUE DEFAULT NULL,
  `title` varchar(16) DEFAULT NULL,
  `color` varchar(16) DEFAULT '#000000',
  `customCSS` tinytext DEFAULT NULL,
  `accesses` tinytext DEFAULT NULL,
  `icon` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;"; // TODO: change DB ENGINE / CHARSET if needed

if ($db->query($sql) === false) {
    error_log('Create table "status" error: ' . $db->error);
    die('Create table "status" error. See log for details.');
}

$statuses = array(
    'admin' => array('title' => 'Клавомеханик', 'accesses' => 'full'),
    'org' => array('title' => 'Организатор', 'color' => 'orange', 'accesses' => 'view_stat,create_compn,allow_msg'),
    'vip' => array('title' => 'VIP', 'icon' => 'data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxMi4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAgLS0+DQo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiIFsNCgk8IUVOVElUWSBuc19mbG93cyAiaHR0cDovL25zLmFkb2JlLmNvbS9GbG93cy8xLjAvIj4NCgk8IUVOVElUWSBuc19zdmcgImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCgk8IUVOVElUWSBuc194bGluayAiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+DQpdPg0KPHN2ZyAgdmVyc2lvbj0iMS4xIiB4bWxucz0iJm5zX3N2ZzsiIHhtbG5zOnhsaW5rPSImbnNfeGxpbms7IiB4bWxuczphPSJodHRwOi8vbnMuYWRvYmUuY29tL0Fkb2JlU1ZHVmlld2VyRXh0ZW5zaW9ucy8zLjAvIg0KCSB3aWR0aD0iMTI5IiBoZWlnaHQ9IjEyNCIgdmlld0JveD0iLTAuNyAtMC4yIDEyOSAxMjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgLTAuNyAtMC4yIDEyOSAxMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGRlZnM+DQo8L2RlZnM+DQo8cG9seWdvbiBvcGFjaXR5PSIwLjU3IiBzdHJva2U9IiMwMDAwMDAiIHBvaW50cz0iNjUuMSw2LjUgODQsNDQuOCAxMjYuMyw1MC45IDk1LjcsODAuNyAxMDIuOSwxMjIuOCA2NS4xLDEwMyAyNy4zLDEyMi44IA0KCTM0LjUsODAuNyAzLjksNTAuOSA0Ni4yLDQ0LjggIi8+DQo8cG9seWdvbiBmaWxsPSIjRkZENDMxIiBzdHJva2U9IiNCRjAwMDAiIHN0cm9rZS13aWR0aD0iMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBwb2ludHM9IjYyLjcsMS41IA0KCTgxLjYsMzkuOCAxMjMuOSw0NS45IDkzLjMsNzUuOCAxMDAuNSwxMTcuOSA2Mi43LDk4IDI0LjksMTE3LjkgMzIuMSw3NS44IDEuNSw0NS45IDQzLjgsMzkuOCAiLz4NCjxnPg0KCTxsaW5lYXJHcmFkaWVudCBpZD0iWE1MSURfMV8iIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iNjIuNjc2OCIgeTE9IjE0LjA1NzYiIHgyPSI2Mi42NzY4IiB5Mj0iMTA1Ljg5NzkiPg0KCQk8c3RvcCAgb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojRkZGRkZGIi8+DQoJCTxzdG9wICBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiNGRkRCMDAiLz4NCgk8L2xpbmVhckdyYWRpZW50Pg0KCTxwYXRoIGZpbGw9InVybCgjWE1MSURfMV8pIiBkPSJNNDcuOCw0NC4zYzAsMC0yNC45LDMuNi0zMy40LDQuOGM2LjIsNiwyNC4xLDIzLjUsMjQuMSwyMy41cy00LjIsMjQuOC01LjcsMzMuMg0KCQljNy42LTQsMjkuOC0xNS43LDI5LjgtMTUuN3MyMi4yLDExLjcsMjkuOCwxNS43Yy0xLjUtOC41LTUuNy0zMy4yLTUuNy0zMy4yczE4LTE3LjUsMjQuMS0yMy41Yy04LjUtMS4yLTMzLjQtNC44LTMzLjQtNC44DQoJCVM2Ni41LDIxLjgsNjIuNywxNC4xQzU4LjksMjEuOCw0Ny44LDQ0LjMsNDcuOCw0NC4zeiIvPg0KPC9nPg0KPC9zdmc+DQo='),
    'personal668817' => array('title' => 'Титан', 'color' => '#000000', 'customCSS' => 'font-weight: bold;'),
    'personal111001' => array('customCSS' => 'font-weight: bold; text-decoration: line-through;'),
    'foolsday' => array('customCSS' => 'transform:rotate(180deg);')
);

$statuses_id = array();

echo 'loading $statuses to table status<br>';

$sql = 'INSERT INTO `status` (name, title, color, customCSS, accesses, icon) VALUES (?, ?, ?, ?, ?, ?)';
$stmt = $db->prepare($sql);

if ($stmt === false) {
    error_log('SQL prepare error: ' . $db->error);
    die('SQL prepare error. See log for details.');
}

foreach ($statuses as $name => $data) {
    $stmt->bind_param('ssssss', $name, $data['title'], $data['color'], $data['customCSS'], $data['accesses'], $data['icon']);

    if (!$stmt->execute()) {
        error_log('SQL execute error: ' . $db->error);
        die('SQL execute error. See log for details.');
    }

    $statuses_id[$name] = $db->insert_id;
}

echo 'creating table userstatus: id | user_id | status_id | since | until | enabled<br>';

$sql = 'CREATE TABLE `userstatus` (
  `id` int(11) KEY AUTO_INCREMENT,
  `user_id` int(11) UNIQUE DEFAULT 0,
  `status_id` int(11) DEFAULT 0,
  `since` datetime DEFAULT NULL, 
  `until` datetime DEFAULT NULL,
  `enabled` bool DEFAULT true,
  FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;'; // TODO: change DB ENGINE / CHARSET if needed

if ($db->query($sql) === false) {
    error_log('Create table "userstatus" error: ' . $db->error);
    die('Create table "userstatus" error. See log for details.');
}

$user_statuses = array(
    'admin' => array('ids' => array(21, 82885, 123190, 474104)),
    'org' => array('ids' => array(233444, 261337, 405687, 572711)),
    'vip' => array('ids' => array(73879)),
    'personal668817' => array('ids' => array(668817)),
    'personal111001' => array('ids' => array(111001)),
    'foolsday' => array('ids' => array(111), 'since' => '2023-04-01 00:00:00', 'until' => '2023-04-01 23:59:59')
);

echo 'loading $user_statuses to table userstatus<br>';

$sql = 'INSERT INTO `userstatus` (user_id, status_id, since, until) VALUES (?, ?, ?, ?)';
$stmt = $db->prepare($sql);

if ($stmt === false) {
    error_log('SQL prepare error: ' . $db->error);
    die('SQL prepare error. See log for details.');
}

foreach ($user_statuses as $name => $data) {
    foreach ($data['ids'] as $user_id) {
        $stmt->bind_param('iiss', $user_id, $statuses_id[$name], $data['since'], $data['until']);

        if (!$stmt->execute()) {
            error_log('SQL execute error: ' . $db->error);
            die('SQL execute error. See log for details.');
        }
    }
}

echo 'all done, exiting... <br>';
