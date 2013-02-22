<?php

class Player
{
	public $uid;
	public $card;
	public $name;
	public $abonement;
	public $penalty;
	public $timestart;
	public $timeend;
	public $played;
	public $table;
	public $status;

	public function PushToTable($table)
	{
		$table->players[$uid] = $this;
	}

	public function Create($uid)
	{
		include '../../engine/bd.php';
		mysql_set_charset('utf8');
		$query = 'select u.id as id, u.card_number as card, u.name as name, u.surname as surname, d.aboniment as abon from users u, dengi d where u.id='.$uid.' and u.card_number = d.card_number';
		$q = mysql_query($query, $db);
		if (!$q) {
			die('Неверный запрос: ' . mysql_error());
		}
		$res = mysql_fetch_array($q);

		$this->uid = $res['id'];
		$this->card = $res['card'];
		$this->name = $res['name'].' '.$res['surname'];
		$this->abonement = $res['abon'];
		$this->timestart = time();
		$this->table = 'none';
		$this->status = 'waiting';
	}

	function __construct($uid)
	{
		$this->Create($uid);
	}
}

class Table
{
	public $players = array();
	public $timestart;
	public $timestop;
	public $type = '';
	public $played = 0;

	public function AddPlayer($player)
	{
		//$player->status = 'in play';
		//$player->table = $this->type;
		$this->players[$player->uid] = $player;
		$this->players[$player->uid]->status = 'in play';
		$this->players[$player->uid]->table = $this->type;
	}

	public function FreeTable()
	{
		$this->players = array();
	}

	public function DeletePlayer($player)
	{
		$this->players[$player->uid]->status = 'frozen';
		unset($this->players[$player->uid]);
	}

	function __construct($type_value)
	{
		$this->type = $type_value;
		$this->timestart = time();
	}
}