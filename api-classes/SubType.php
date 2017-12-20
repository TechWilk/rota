<?php

class SubType
{
    protected $id;
    public $name;
    public $description;

    protected $db_table = 'eventSubTypes';

    public function getId()
    {
        return $this->id;
    }

    public function createInDb(Database $db)
    {
        if (empty($this->name) || empty($this->description)) {
            throw new Exception('Series name ('.$this->name.') or description ('.$this->description.') cannot be empty.');
        }

        $data = [
      ['field' => 'name', 'type' => 'string', 'value' => $this->name],
      ['field' => 'description', 'type' => 'string', 'value' => $this->description],
    ];

        $db->insert($this->db_table, $data);

        $this->id = $db->lastInsertId();

        return true;
    }

    public function DeleteFromDbWithId(Database $db, $id = null)
    {
        if ($id == null) {
            $id = $this->id;
        }
        if ($id == null) {
            throw new Exception('Sub Type id cannot be null');
        }

        $whereCondition = 'id = '.$id;
        $where = [
      $whereCondition,
    ];

        if ($db->delete($this->db_table, $where)) {
            return true;
        } else {
            return false;
        }
    }

    public function getFromDbWithId(Database $db, $id = null)
    {
        if ($id == null) {
            $id = $this->id;
        }
        if ($id == null) {
            throw new Exception('Sub Type id cannot be null');
        }

        $columns = [
      'id',
      'name',
      'description',
    ];

        $whereCondition = 'id = '.$id;
        $where = [
      $whereCondition,
    ];

        $result = $db->selectSingle('SubType', $this->db_table, $columns, $where);

        $this->id = $result->id;
        $this->name = $result->name;
        $this->description = $result->description;

        return true;
    }
}