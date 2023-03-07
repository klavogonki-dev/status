<?php

// after initdb
require_once('../db_3.1_b1.php');

switch ($_POST['action']) {
    case 'viewstatuses':
        $res = $db->query('SELECT * FROM `status`');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'updatestatus':
        $level_link = (int)$_POST['levelLink'];

        if ($level_link < 0 or $level_link > 9) {
            die('There is no such rank');
        }

        if ($level_link === 0) {
            $level_link = null;
        }

        $tmp_name = $_FILES['icon']['tmp_name'];

        if (!$tmp_name) {
            $icon = null;
        }
        else {
            if (!is_uploaded_file($tmp_name)) {
                die('Something wrong with uploaded icon');
            }

            if (strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION)) !== 'png') {
                die('Icon extension is not png');
            }

            $icon = 'data:image/png;base64,' . base64_encode(file_get_contents($tmp_name));
        }

        $sql = 'INSERT INTO `status` (name, title, color, customCSS, accesses, icon, levelLink) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title = ?, color = ?, customCSS = ?, accesses = ?, icon = ?, levelLink = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL updatestatus prepare error: ' . $db->error);
            die('SQL updatestatus prepare error. See log for details.');
        }

        $accesses = $_POST['accesses'] ?: null;
        $stmt->bind_param('ssssssisssssi', $_POST['codename'], $_POST['title'], $_POST['color'], $_POST['customCSS'], $accesses, $icon, $level_link, $_POST['title'], $_POST['color'], $_POST['customCSS'], $accesses, $icon, $level_link);

        if ($stmt->execute()) {
            echo 'Update status success';
        }
        else {
            error_log('Update status fail: ' . $db->error);
            echo 'Update status fail. See log for details.';
        }

        break;
    case 'deletestatus':
        $sql = 'DELETE FROM `status` WHERE name = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL deletestatus prepare error: ' . $db->error);
            die('SQL deletestatus prepare error. See log for details.');
        }

        $stmt->bind_param('s', $_POST['codename']);

        if ($stmt->execute()) {
            if ($stmt->affected_rows === 1) {
                echo 'Delete status success';
            }
            else {
                echo 'There is no such status name in DB';
            }
        }
        else {
            error_log('Delete status fail: ' . $db->error);
            echo 'Delete status fail. See log for details.';
        }

        break;
    case 'viewuserstatuses':
        $res = $db->query('SELECT us.id, us.user_id, s.name, us.since, us.until, us.enabled FROM userstatus AS us, status AS s WHERE us.status_id = s.id');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'updateuserstatus':
        $sql = 'INSERT INTO `userstatus` (user_id, status_id, since, until) VALUES (?, (SELECT id FROM status WHERE name = ?), ?, ?) ON DUPLICATE KEY UPDATE status_id = VALUES(status_id), since = VALUES(since), until = VALUES(until)';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL updateuserstatus prepare error: ' . $db->error);
            die('SQL updateuserstatus prepare error. See log for details.');
        }

        $since = $_POST['since'] ?: null;
        $until = $_POST['until'] ?: null;
        $stmt->bind_param('isss', $_POST['user_id'],$_POST['codename'], $since, $until);

        if ($stmt->execute()) {
            echo 'Update user status success';
        } else {
            error_log('Update user status fail: ' . $db->error);
            echo 'Update user status fail. See log for details.';
        }

        break;
    case 'switchuserstatusenabled':
        $sql = 'UPDATE `userstatus` SET enabled = enabled XOR 1 WHERE user_id = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL switchuserstatusenabled prepare error: ' . $db->error);
            die('SQL switchuserstatusenabled prepare error. See log for details.');
        }

        $stmt->bind_param('i', $_POST['user_id']);

        if ($stmt->execute()) {
            if ($stmt->affected_rows === 1) {
                echo 'Switch user status enabled success';
            }
            else {
                echo 'There is no such user ID in DB';
            }
        }
        else {
            error_log('Switch user status enabled fail: ' . $db->error);
            echo 'Switch user status enabled. See log for details.';
        }

        break;
    case 'deleteuserstatus':
        $sql = 'DELETE FROM `userstatus` WHERE user_id = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL deleteuserstatus prepare error: ' . $db->error);
            die('SQL deleteuserstatus prepare error. See log for details.');
        }

        $stmt->bind_param('i', $_POST['user_id']);

        if ($stmt->execute()) {
            if ($stmt->affected_rows === 1) {
                echo 'Delete user status success';
            }
            else {
                echo 'There is no such user ID in DB';
            }
        }
        else {
            error_log('Delete user status fail: ' . $db->error);
            echo 'Delete user status fail. See log for details.';
        }

        break;
    case 'viewalluseraccesses':
        $res = $db->query('SELECT user_id, accesses FROM status AS s, userstatus AS us WHERE us.status_id = s.id AND us.enabled = true AND (since IS NULL OR since<=NOW()) AND (until IS NULL OR NOW()<=until) AND accesses IS NOT NULL');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'viewuseraccesses':
        $sql = 'SELECT accesses FROM status AS s, userstatus AS us WHERE us.user_id = ? AND us.status_id = s.id AND us.enabled = true AND (since IS NULL OR since<=NOW()) AND (until IS NULL OR NOW()<=until) AND accesses IS NOT NULL';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL viewuseraccesses prepare error: ' . $db->error);
            die('SQL viewuseraccesses prepare error. See log for details.');
        }

        $stmt->bind_param('i', $_POST['user_id']);

        if (!$stmt->execute()) {
            error_log('Execute view user accesses fail: ' . $db->error);
            die('Execute view user accesses fail. See log for details.');
        }

        $result = $stmt->get_result();

        if ($result === false) {
            error_log('Result view user accesses error: ' . $db->error);
            die('Result view user accesses error. See log for details.');
        }

        $result_num = $result->num_rows;

        if ($result_num === 0) {
            echo "User doesn't have accesses";
        }
        else {
            echo 'User accesses: ' . htmlspecialchars($result->fetch_assoc()['accesses']);
        }

        break;
    default:
        echo 'Not implemented: ' . htmlspecialchars($_POST['action']);
        break;
}
