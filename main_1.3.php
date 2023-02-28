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
		switch ($key)
		{
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
		$sql = "SELECT title, color, customCSS, accesses, icon from status as s, userstatus as us where us.user_id in (?, ?) and us.status_id=s.id and us.enabled=true and (since is null or since<=NOW()) and (until is null or NOW()<=until) order by field (us.user_id, ?, ?) limit 1";
		$all_users_magic_id = 111;	//TODO: maybe it should be set in some kind of config?

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("dddd", $this->id, $all_users_magic_id, $this->id, $all_users_magic_id);
		$stmt->execute();
		$rs = $stmt->get_result();
		$row = $rs->fetch_assoc();

		//TODO: filter each value, acquired from DB (if needed)
//		if ($row) {
//			$row = $this->filterData($row);
//		}

		return ($row) ? $row : false;
	}

	public function getStatusIcon()
	{
		return ($this->statusData) ? $this->statusData['icon'] : "";
	}

	public function getStatus()
	{
		return ($this->statusData) ? "Personal" : "Normal";
	}

	public function getTitle()
	{
		return ($this->statusData && $this->statusData['title']) ? $this->statusData['title'] : $this->getRank();
	}

	public function getStyle()
	{
		//style="color: #rrggbb !important; border-color: #rrggbb !important; {$customCSS}"
		if (!$this->statusData) return "";

		$styles = array();
		if ($this->statusData['color'])
		{
			$styles[] = "color:{$this->statusData['color']} !important";
			$styles[] = "border-color:{$this->statusData['color']} !important";
		}

		if ($this->statusData['customCSS'])
		{
			$styles[] = "{$this->statusData['customCSS']}";
		}

		return (!empty($styles)) ? implode('; ', $styles) : "";
	}

/*
	public function filterData($data)
	{
		$rv = array();
		foreach ($data as $key=>$value)
		{
			//check and filter $value, if needed:

			//TODO: implement filtering here

			//return filtered value:
			$rv[$key] = $value;
		}

		return $rv;
	}
*/
}

?>
