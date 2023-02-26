<?php

// after initdb
require_once('../db_3_b1.php');

switch ($_POST['action']) {
    case 'viewstatuses':
        $res = $db->query('SELECT * FROM `status`');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'updatestatus':
        $sql = 'INSERT INTO `status` (name, title, color, customCSS) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE title = ?, color = ?, customCSS = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL updatestatus prepare error: ' . $db->error);
            die('SQL updatestatus prepare error. See log for details.');
        }

        $stmt->bind_param('sssssss', $_POST['codename'], $_POST['title'], $_POST['color'], $_POST['customCSS'], $_POST['title'], $_POST['color'], $_POST['customCSS']);

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
        $sql = 'SELECT id FROM status WHERE name = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL get status ID prepare error: ' . $db->error);
            die('SQL get status ID prepare error. See log for details.');
        }

        $stmt->bind_param('s', $_POST['codename']);

        if (!$stmt->execute()) {
            error_log('Execute get status ID error: ' . $db->error);
            die('Execute get status ID error. See log for details.');
        }

        $result = $stmt->get_result();

        if ($result === false) {
            error_log('Result get status error: ' . $db->error);
            die('Result get status error. See log for details.');
        }

        $result_num = $result->num_rows;

        if ($result_num > 1) {
            die('Duplicate status name in DB');
        }

        if ($result_num === 0) {
            die('There is no such status name in DB');
        }

        $status_id = $result->fetch_assoc()['id'];

        $sql = 'INSERT INTO `userstatus` (user_id, status_id, since, until) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status_id = ?, since = ?, until = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL updateuserstatus prepare error: ' . $db->error);
            die('SQL updateuserstatus prepare error. See log for details.');
        }

        $since = $_POST['since'] ?: null;
        $until = $_POST['until'] ?: null;
        $stmt->bind_param('iississ', $_POST['user_id'],$status_id, $since, $until, $status_id, $since, $until);

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
    default:
        echo 'Not implemented: ' . $_POST['action'];
        break;
}
