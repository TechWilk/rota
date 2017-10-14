<?php

class Series
{
    protected $id;
    public $name;
    public $description;
    protected $archived;

    protected $db_table = 'eventGroups';

    public function getId()
    {
        return $this->id;
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function unarchive()
    {
        $this->archived = false;
    }

    public function isArchived()
    {
        return $this->archived;
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
            throw new Exception('Series id cannot be null');
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

        $result = $db->selectSingle('Series', $this->db_table, $columns, $where);

        $this->id = $result->id;
        $this->name = $result->name;
        $this->description = $result->description;

        return true;
    }

    public function getFromDbWithName(Database $db, $name = null)
    {
        if ($name == null) {
            $name = $this->name;
        }

        if ($name == null) {
            throw new Exception('Series name cannot be null');
        }

        $columns = [
      'id',
      'name',
      'description',
    ];

        $whereCondition = 'name = '.$db->addQuotes($name);
        $where = [
      $whereCondition,
    ];

        $result = $db->selectSingle('Series', $this->db_table, $columns, $where);

        $this->id = $result->id;
        $this->name = $result->name;
        $this->description = $result->description;

        return true;
    }
}
