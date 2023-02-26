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

echo 'creating table status: id | name | title | color | customCSS <br>';

$sql = "CREATE TABLE `status` (
  `id` int(11) KEY AUTO_INCREMENT,
  `name` varchar(16) UNIQUE DEFAULT NULL,
  `title` varchar(16) DEFAULT NULL,
  `color` varchar(16) DEFAULT '#000000',
  `customCSS` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;"; // TODO: change DB ENGINE / CHARSET if needed

if ($db->query($sql) === false) {
    error_log('Create table "status" error: ' . $db->error);
    die('Create table "status" error. See log for details.');
}

$statuses = array(
    'admin' => array('title' => 'Клавомеханик'),
    'org' => array('title' => 'Организатор', 'color' => 'orange'),
    'personal668817' => array('title' => 'Титан', 'color' => '#000000', 'customCSS' => 'font-weight: bold;'),
    'personal111001' => array('customCSS' => 'font-weight: bold; text-decoration: line-through;'),
    'foolsday' => array('customCSS' => 'transform:rotate(180deg);')
);

$statuses_id = array();

echo 'loading $statuses to table status <br>';

$sql = 'INSERT INTO `status` (name, title, color, customCSS) VALUES (?, ?, ?, ?)';
$stmt = $db->prepare($sql);

if ($stmt === false) {
    error_log('SQL prepare error: ' . $db->error);
    die('SQL prepare error. See log for details.');
}

foreach ($statuses as $name => $data) {
    $stmt->bind_param('ssss', $name, $data['title'], $data['color'], $data['customCSS']);

    if (!$stmt->execute()) {
        error_log('SQL execute error: ' . $db->error);
        die('SQL execute error. See log for details.');
    }

    $statuses_id[$name] = $db->insert_id;
}

echo 'creating table userstatus: id | user_id | status_id | since | until <br>';

$sql = 'CREATE TABLE `userstatus` (
  `id` int(11) KEY AUTO_INCREMENT,
  `user_id` int(11) UNIQUE DEFAULT 0,
  `status_id` int(11) DEFAULT 0,
  `since` datetime DEFAULT NULL, 
  `until` datetime DEFAULT NULL,
  `enabled` bool DEFAULT 1,
  FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;'; // TODO: change DB ENGINE / CHARSET if needed

if ($db->query($sql) === false) {
    error_log('Create table "userstatus" error: ' . $db->error);
    die('Create table "userstatus" error. See log for details.');
}

$user_statuses = array(
    'admin' => array('ids' => array(21, 82885, 123190, 474104)),
    'org' => array('ids' => array(233444, 261337, 405687, 572711)),
    'personal668817' => array('ids' => array(668817)),
    'personal111001' => array('ids' => array(111001)),
    'foolsday' => array('ids' => array(111), 'since' => '2023-04-01 00:00:00', 'until' => '2023-04-02 00:00:00')
);

echo 'loading $user_statuses to table userstatus <br>';

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
