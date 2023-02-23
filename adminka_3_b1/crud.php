<?php

//after initdb
require_once('../db_3_b1.php');

function get_status_id($status_name) {
    global $db;

    $sql = 'SELECT id FROM status WHERE name = ?';
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        error_log('SQL prepare error: ' . $db->error);
        die('SQL prepare error. See log for details.');
    }

    $stmt->bind_param('s', $status_name);

    if (!$stmt->execute()) {
        error_log('SQL execute error: ' . $db->error);
        die('SQL execute error. See log for details.');
    }

    $result = $stmt->get_result();

    if ($result === false) {
        error_log('SQL get result error: ' . $db->error);
        die('SQL get result error. See log for details.');
    }

    $result_num = $result->num_rows;

    if ($result_num > 1) {
        die('Duplicate status name in DB');
    }

    if ($result_num === 1) {
        return $result->fetch_assoc()['id'];
    }

    return null;
}

switch ($_POST['action']) {
    case 'viewstatuses':
        $res = $db->query('SELECT * FROM `status`');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'updatestatus':
        $status_id = get_status_id($_POST['codename']);

        if ($status_id === null) {
            $sql = 'INSERT INTO `status` (name, title, color, customCSS) VALUES (?, ?, ?, ?)';
            $stmt = $db->prepare($sql);

            if ($stmt === false) {
                error_log('SQL prepare error: ' . $db->error);
                die('SQL prepare error. See log for details.');
            }

            $stmt->bind_param('ssss', $_POST['codename'], $_POST['title'], $_POST['color'], $_POST['customCSS']);

            if ($stmt->execute()) {
                echo 'Success';
            }
            else {
                echo 'Failed';
            }

            exit;
        }

        $sql = 'UPDATE `status` SET title = ?, color = ?, customCSS = ? WHERE id = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL prepare error: ' . $db->error);
            die('SQL prepare error. See log for details.');
        }

        $stmt->bind_param('sssi', $_POST['title'], $_POST['color'], $_POST['customCSS'], $status_id);

        if ($stmt->execute()) {
            echo 'Success';
        }
        else {
            echo 'Failed';
        }

        break;
    case 'deletestatus':
        $sql = 'DELETE FROM `status` WHERE name = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL prepare error: ' . $db->error);
            die('SQL prepare error. See log for details.');
        }

        $stmt->bind_param('s', $_POST['codename']);

        if ($stmt->execute()) {
            echo 'Success';
        }
        else {
            echo 'Failed';
        }

        break;
    case 'viewuserstatuses':
        $res = $db->query('SELECT us.id, us.user_id, s.name, us.since, us.until FROM userstatus AS us, status AS s WHERE us.status_id = s.id');

        while ($row = $res->fetch_assoc()) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . '<br>';
        }

        break;
    case 'updateuserstatus':
        $status_id = get_status_id($_POST['codename']);

        if ($status_id === null) {
            die('There is no such status name in DB');
        }

        $sql = 'SELECT id FROM `userstatus` WHERE user_id = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL prepare error: ' . $db->error);
            die('SQL prepare error. See log for details.');
        }

        $stmt->bind_param('i', $_POST['user_id']);

        if (!$stmt->execute()) {
            error_log('SQL execute error: ' . $db->error);
            die('SQL execute error. See log for details.');
        }

        $result = $stmt->get_result();

        if ($result === false) {
            error_log('SQL get result error: ' . $db->error);
            die('SQL get result error. See log for details.');
        }

        $result_num = $result->num_rows;

        if ($result_num > 1) {
            die('User has more than one status in DB');
        }

        if ($result_num === 1) {
            $sql = 'UPDATE `userstatus` SET status_id = ?, since = FROM_UNIXTIME(?), until = FROM_UNIXTIME(?) WHERE id = ?';
            $stmt = $db->prepare($sql);

            if ($stmt === false) {
                error_log('SQL prepare error: ' . $db->error);
                die('SQL prepare error. See log for details.');
            }

            $stmt->bind_param('iiii', $status_id, $_POST['since'], $_POST['until'], $result->fetch_assoc()['id']);

            if ($stmt->execute()) {
                echo 'Success';
            }
            else {
                echo 'Failed';
            }

            exit;
        }

        $sql = 'INSERT INTO `userstatus` (user_id, status_id, since, until) VALUES (?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL prepare error: ' . $db->error);
            die('SQL prepare error. See log for details.');
        }

        $stmt->bind_param('iiii', $_POST['user_id'], $status_id, $_POST['since'], $_POST['until']);

        if ($stmt->execute()) {
            echo 'Success';
        }
        else {
            echo 'Failed';
        }

        break;
    case 'deleteuserstatus':
        $sql = 'DELETE FROM `userstatus` WHERE user_id = ?';
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            error_log('SQL prepare error: ' . $db->error);
            die('SQL prepare error. See log for details.');
        }

        $stmt->bind_param('i', $_POST['user_id']);

        if ($stmt->execute()) {
            echo 'Success';
        }
        else {
            echo 'Failed';
        }

        break;
    default:
        echo 'todo: ' . $_POST['action'];
        break;
}
