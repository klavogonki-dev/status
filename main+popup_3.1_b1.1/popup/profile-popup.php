<?php
require_once('../../main_3.2_b1.4.php');
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../main_1.2.css">
</head>
<body>

<div class=popup_profile>
    <table class=title>
        <tr>
            <th>
                <div class="avatar_big">
                    <img src="http://klavogonki.ru/storage/avatars/<?= $user->id; ?>_big.png">
                </div>
            </th>
            <td>
                <div class="rang<?= $user->level; ?> status<?= $user->status; ?>"
                     style="<?= $user->style; ?>">
                    <img class="status-icon" src="<?=$user->statusIcon;?>"><?= $user->title; ?></div>
                <div class="name">user<?= $user->id; ?></div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
