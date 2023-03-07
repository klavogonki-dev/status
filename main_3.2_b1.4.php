<?php

require_once('db_3.1_b1.php');

$user = new uinfo();

$user->db = $db; // TODO: remove if not needed here and change corresponding code on query execute

$user->id = (int)$_POST['user_id'];
$user->best_speed = (int)$_POST['record'];
//!!! ↓ order matters
$user->getKey('level');
$user->getKey('statusData');
$user->getKey('status');
$user->getKey('title');
$user->getKey('style');
$user->getKey('statusIcon');

class uinfo
{
    public $id;
    public $best_speed;
    public $level;
    public $statusData;
    public $statusIcon;
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

            case 'statusIcon':
                $this->statusIcon = $this->getStatusIcon();
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
        $sql = 'SELECT title, color, customCSS, accesses, icon FROM status AS s, userstatus AS us WHERE us.user_id IN (?, ?) AND us.status_id = s.id AND us.enabled = true AND (since IS NULL OR since<=NOW()) AND (until IS NULL OR NOW()<=until) AND (levelLink IS NULL OR levelLink = ?) ORDER BY FIELD(us.user_id, ?, ?) LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $all_users_magic_id = 111;
        $stmt->bind_param('iiiii', $this->id, $all_users_magic_id, $this->level, $this->id, $all_users_magic_id);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();

        return $row ?: false;
    }

    public function getStatusIcon()
    {
        return ($this->statusData) ? $this->statusData['icon'] : '';
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
