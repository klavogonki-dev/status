<?php

require_once('db_1.php');

$user = new uinfo();

$user->db = $db;	//TODO: remove if not needed here and change corresponding code on query execute

$user->id = (int)$_POST["user_id"];
$user->best_speed = (int)$_POST["record"];
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
	public $titleData;
	public $status;
	public $title;
	public $style;

	public $db;

	public function getKey($key)
	{
		switch ($key)
		{
			case 'level':
				$this->level = $this->getLevel();
				break;

			case 'statusData':
				$this->titleData = $this->getStatusDataFromDb();
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
		if (!$this->best_speed)
		{
			return 1;
		}
		if ($this->best_speed >= 800)
		{
			return 9;
		}
		return intdiv($this->best_speed, 100) + 1;
	}

	public function getRank()
	{
		switch($this->level)
		{				
			case 1: return 'Новичок';
			case 2: return 'Любитель';				
			case 3: return 'Таксист';
			case 4: return 'Профи';
			case 5: return 'Гонщик';
			case 6: return 'Маньяк';
			case 7: return 'Супермен';
			case 8: return 'Кибергонщик';
			case 9: return 'Экстракибер';
		}		
	}
	
	public function getStatusDataFromDb()
	{
		$sql = "SELECT title, color from status as s, userstatus as us where us.user_id=? and us.status_id=s.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("d", $this->id);
		$stmt->execute();
		$rs = $stmt->get_result();
		$row = $rs->fetch_assoc();

		return ($row) ? $row : false;
	}

	public function getStatus()
	{
		return ($this->titleData) ? "Personal" : "Normal";
	}

	public function getTitle()
	{
		return ($this->titleData) ? $this->titleData['title'] : $this->getRank();
	}

	public function getStyle()
	{
		//style="color: #rrggbb !important; border-color: #rrggbb !important"
		return ($this->titleData && $this->titleData['color']) ? "color:{$this->titleData['color']} !important; border-color:{$this->titleData['color']} !important" : "";
	}
}

?>
