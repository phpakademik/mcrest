<?php 

namespace Framework\Db;

use Framework\Db\Database;

/**
 * 
 */
abstract class Model extends Database
{

	public $stmt;
	public $sql;
	public $where;
	public $fields;
	public $count;
	public $fetch;
	public $data = [];

	public function where($data = [])
	{
		$key;
		$value;
		foreach ($data as $keys => $values) 
		{
			$key  = $keys;
			$value = $values;
		}
	 return	$sql = " WHERE {$key}={$value}";
		// return $this;
	}

	public function findAll()
	{
		$sql = "SELECT * from {$this->table}" . self::where();
		$this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
		// return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $this;
	}
	public function findById($id)
	{
		$sql = "SELECT * from {$this->table} where id={$id}";
		$this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();	
		return $this;
	}
	public function query($sql)
	{
		$this->stmt = $this->pdo->prepare($sql);
		return $this->stmt->execute();
		// return $this;
	}
}