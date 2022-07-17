<?php

namespace Framework\Db;

use Framework\Db\Database;
use \PDO;

abstract class Model extends Database
{
    private $stmt;
    private $data = array();
    private $sql;
    private $where;
    private $fields;
    private $count;
    private $fetch;
    private $lastId;

    public function __construct()
    {
        parent::__construct();
        self::setTable();
        self::setPrimaryKey();
    }

    public function setTable()
    {
        if ( ! isset($this->table)) {
            $modelName = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($modelName);
        }
    }

    public function setPrimaryKey()
    {
        if ( ! isset($this->pk)) {
            $this->pk = 'id';
        }
    }

    public function param($data = null)
    {
        if (empty($data)) {
            $data = $this->data['conditions'];
        }

        foreach ($data as $k => $v) {
            $tipo = (is_int($v)) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $this->stmt->bindValue(":{$k}", $v, $tipo);
        }
    }

    public function fields($data = null)
    {
        if (empty($data) && isset($this->data['fields'])) {
            return implode(',', $this->data['fields']);
        }

        if ( ! empty($data)) {
            foreach ($data as $k => $v) {
                $fields[] = $k;
            }
            return implode(',', $fields);
        }

        return '*';
    }

    public function conditions($separator)
    {
        $param = [];
        foreach ($this->data['conditions'] as $k => $v) {
            $param[] = "{$k} = :{$k}";
        }

        return implode($separator, $param);
    }

    public function where()
    {
        return $this->where = (isset($this->data['conditions']))
        ? 'WHERE ' . self::conditions(' AND ')
        : '';
    }

    public function find()
    {
        $sql = "SELECT " . self::fields() . " FROM {$this->table} " . self::where();

        $this->stmt = $this->pdo->prepare($sql);

        if ( ! empty($this->where)) {
            self::param();
        }

        $this->stmt->execute();
        return $this;
    }

    public function values()
    {
        foreach ($this->data as $k => $v) {
            $values[] = ":{$k}";
        }

        return implode(',', $values);
    }

    private function insertQueryString()
    {
        $fields = self::fields($this->data);
        $values = self::values();

        return "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
    }

    public function updateWhere($data)
    {
        $this->data['conditions'] = [$this->pk => $data[$this->pk]];
        $where = 'WHERE ' . self::conditions('');
        unset($data[$this->pk]);

        return $where;
    }

    private function updateQueryString($data)
    {
        $this->data['conditions'] = $data;
        $fields = self::conditions(',');

        return "UPDATE {$this->table} SET {$fields} {$this->where}";
    }

    public function findAll($data = null)
    {
        $this->data = $data;
        return $this->find()
        ->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($data)
    {
        $this->data['conditions'] = $data;
        return $this->fetch = $this->find()
        ->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        return self::findOne([$this->pk => $id]);
    }

    public function exists($id)
    {
        if (is_array($id)) {
            return (self::findOne($id));
        }

        return (self::findById($id));
    }

    public function fetch()
    {
        return $this->fetch;
    }

    private function lastSavedId()
    {
        $id = $this->pdo->lastInsertId();
        return ($id) ? $id : $this->lastId;
    }

    public function query($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
        $result      = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->count = count($result);

        return $result;
    }

    public function save($data)
    {
        if (array_key_exists($this->pk, $data)) {
            $this->count = $this->findOne([$this->pk => $data[$this->pk]]);
            $this->lastId = $data[$this->pk];
        }

        if ( ! empty($this->count)) {
            return $this->update($data);
        }

        return $this->create($data);
    }

    public function update($data)
    {
        if ( ! array_key_exists($this->pk, $data)) {
            return false;
        }

        $param       = $data;
        $this->where = self::updateWhere($data);
        $this->stmt  = $this->pdo->prepare(self::updateQueryString($data));
        self::param($param);
        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

    public function create($data)
    {
        $this->data = $data;

        $this->stmt = $this->pdo->prepare(self::insertQueryString());
        self::param($data);

        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

    public function delete($data)
    {
        $this->data['conditions'] = $data;

        $sql = "DELETE FROM {$this->table} " . self::where();
        $this->stmt = $this->pdo->prepare($sql);

        if ( ! empty($this->where)) {
            self::param();
        }

        $this->stmt->execute();
        $this->count = $this->stmt->rowCount();
    }

}
