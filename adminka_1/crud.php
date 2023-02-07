<?php

//after initdb
require_once('../db_1.php');

switch ($_POST['action']) {
	case 'viewstatuses':
		$res = $db->query("SELECT * FROM status");
		while ($row = $res->fetch_assoc()) {
			echo json_encode($row).'<br>';
		}
		break;

	case 'viewuserstatuses':
		$res = $db->query("SELECT us.id, us.user_id, s.name, s.title, s.color FROM userstatus as us, status as s where us.status_id = s.id");
		while ($row = $res->fetch_assoc()) {
			echo json_encode($row).'<br>';
		}
		break;

	default: 
		echo 'todo: '.$_POST['action'];
		break;
}

?>