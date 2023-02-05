<?php

$user = new uinfo();
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

	public function getStatus($key)
	{
		if($key == 'level')
		{
			$this->level = $this->getLevel();
		}
		if($key == 'status')
		{		
			$this->status = $this->id == 668817 ? 'Personal' : 'Normal'; //todo: change to getTitleFromDb()
		}
		if($key == 'title')
		{		
			$this->title = $this->getTitle(); //todo: change to getTitleFromDb()
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
	
	public function getTitle()
	{
		if(in_array($this->id, array(21, 82885, 123190, 474104)))
			return 'Клавомеханик';
		if($this->id == 668817)
			return 'Титан';			
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
	public function getTitleFromDb()
	{
		$rs = $db->query('SELECT status, color FROM ustatuses WHERE user_id=?', $this->id);
		//...
	}
}

class db
{
	public function query($sql, $user_id) //mock
	{
		return array(
			474104 => array("status" => "Клавомеханик", "color" => "rang"),
			668817 => array("status" => "Титан"       , "color" => "#000000"),
			217625 => array("status" => "Мастер"      , "color" => "#bc0143"),
			171789 => array("status" => "Организатор" , "color" => "orange"),
			 73879 => array("status" => "Магнат"      , "color" => "goldenrod"),
			211962 => array("status" => "Редактор"    , "color" => "royalblue")
		)[$user_id];
	}
}

?>
