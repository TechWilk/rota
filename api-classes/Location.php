<?php

class Location
{
    protected $id;
    public $name;
    public $address;

    protected $db_table = 'locations';

    public function getId()
    {
        return $this->id;
    }

    public function createInDb(Database $db)
    {
        if (empty($this->name)) {
            throw new Exception('Series name ('.$this->name.') cannot be empty.');
        }

        $data = [
      ['field' => 'name', 'type' => 'string', 'value' => $this->name],
      ['field' => 'address', 'type' => 'string', 'value' => $this->address],
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
            throw new Exception('Location id cannot be null');
        }

        $columns = [
      'id',
      'name',
      'address',
    ];

        $whereCondition = 'id = '.$id;
        $where = [
      $whereCondition,
    ];

        $result = $db->selectSingle('Location', $this->db_table, $columns, $where);

        $this->id = $result->id;
        $this->name = $result->name;
        $this->address = $result->address;

        return true;
    }
}
