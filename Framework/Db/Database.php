<?php 

namespace Framework\Db;
require_once __DIR__.'/../../config/main.php';


abstract class Database
{
	
	public $pdo;

	public function __construct()
	{
		// $this->pdo = $this->Connect();
		$this->pdo = new \PDO($config['db']['dns'],$config['db']['user'],$config['db']['password']);
		// $this->con = mysqli_connect('localhost','root','','mcrest');
	}
	public function Connect()
	{
		$pdo = new \PDO($config['db']['dns'],$config['db']['user'],$config['db']['password']);
		return $pdo;
	}
}