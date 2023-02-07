<?php

require_once('db_1.php');

$user = new uinfo();

$user->db = $db;	//TODO: remove if not needed here and change corresponding code on query execute

$user->id = (int)$_POST["user_id"];
$user->best_speed = (int)$_POST["record"];
$user->getStatus('level');
$user->getStatus('status');
$user->getStatus('title');

class uinfo
{
	public $id;
	public $best_speed;
	public $level;
	public $status;
	public $title;

	public $db;

	public function getStatus($key)
	{
		if($key == 'level')
		{
			$this->level = $this->getLevel();
		}
		if($key == 'status')
		{
			//should be 'Normal' or 'Personal'!
			$this->status = $this->getStatusFromDb();
		}
		if($key == 'title')
		{
			$this->title = $this->getTitleFromDb();
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
	
	public function getStatusFromDb()
	{
		//TODO: I bet this can be simplified
		$sql = "SELECT count(*) from status as s, userstatus as us where us.user_id=? and us.status_id=s.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("d", $this->id);
		$stmt->execute();
		$rs = $stmt->get_result();
		$row = $rs->fetch_row();

		return ($row[0] == 0) ? "Normal" : "Personal";
	}

	public function getTitleFromDb()
	{
		$sql = "SELECT title from status as s, userstatus as us where us.user_id=? and us.status_id=s.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("d", $this->id);
		$stmt->execute();
		$rs = $stmt->get_result();
		$row = $rs->fetch_assoc();

		return ($row) ? $row['title'] : $this->getRank();
	}
}

?>
