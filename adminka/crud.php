<?php

//after initdb
try {
	$db = new mysqli("localhost", "root", "root", "kgru");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:root, db:kgru");
}

if ($_POST['action'] == 'viewstatuses') {
	$res = $db->query("SELECT * FROM status");
	while ($row = $res->fetch_assoc()) {
		echo json_encode($row).'<br>';
	}
} else {
	echo 'todo: '.$_POST['action'];
}

?>