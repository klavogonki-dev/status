<?php

require_once('db_3_b1.php');

$user = new uinfo();

$user->db = $db; //TODO: remove if not needed here and change corresponding code on query execute

$user->id = (int)$_POST['user_id'];
$user->best_speed = (int)$_POST['record'];
//!!! ↓ order matters
$user->getKey('level');
$user->getKey('statusData');
$user->getKey('status');
$user->getKey('title');
$user->getKey('style');

class uinfo
{
    public $id;
    public $best_speed;
    public $level;
    public $statusData;
    public $status;
    public $title;
    public $style;

    public $db;

    public function getKey($key)
    {
        switch ($key) {
            case 'level':
                $this->level = $this->getLevel();
                break;

            case 'statusData':
                $this->statusData = $this->getStatusDataFromDb();
                break;

            case 'status':
                $this->status = $this->getStatus();
                break;

            case 'title':
                $this->title = $this->getTitle();
                break;

            case 'style':
                $this->style = $this->getStyle();
                break;
        }

        return $this;
    }

    public function getLevel()
    {
        if (!$this->best_speed) {
            return 1;
        }
        if ($this->best_speed >= 800) {
            return 9;
        }
        return intdiv($this->best_speed, 100) + 1;
    }

    public function getRank()
    {
        switch ($this->level) {
            case 1:
                return 'Новичок';
            case 2:
                return 'Любитель';
            case 3:
                return 'Таксист';
            case 4:
                return 'Профи';
            case 5:
                return 'Гонщик';
            case 6:
                return 'Маньяк';
            case 7:
                return 'Супермен';
            case 8:
                return 'Кибергонщик';
            case 9:
                return 'Экстракибер';
        }
    }

    public function getStatusDataFromDb()
    {
        date_default_timezone_set('Europe/Moscow');
        $now = strtotime('now');

        $sql = 'SELECT title, color, customCSS, since, until, enabled from status as s, userstatus as us where us.user_id = ? and us.status_id = s.id';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();

        if ($row and $row['enabled'] === 1 and ($row['until'] === null or strtotime($row['since']) <= $now and $now <= strtotime($row['until']))) {
            return $row;
        }

        $id_all = 111;
        $stmt->bind_param('i', $id_all);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();

        if ($row and $row['enabled'] === 1 and ($row['until'] === null or strtotime($row['since']) <= $now and $now <= strtotime($row['until']))) {
            return $row;
        }

        return false;
    }

    public function getStatus()
    {
        return $this->statusData ? 'Personal' : 'Normal';
    }

    public function getTitle()
    {
        return $this->statusData && $this->statusData['title'] ? $this->statusData['title'] : $this->getRank();
    }

    public function getStyle()
    {
        //style="color: #rrggbb !important; border-color: #rrggbb !important; {$customCSS}"
        if (!$this->statusData) {
            return '';
        }

        $styles = array();

        if ($this->statusData['color']) {
            $styles[] = "color:{$this->statusData['color']} !important";
            $styles[] = "border-color:{$this->statusData['color']} !important";
        }

        if ($this->statusData['customCSS']) {
            $styles[] = "{$this->statusData['customCSS']}";
        }

        return (!empty($styles)) ? implode('; ', $styles) : '';
    }
}
