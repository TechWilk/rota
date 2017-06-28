<?php

class Database
{
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "local";
    private $db_name = "database_name";

    private $db_prefix = "";

    private static $con = false;
    private $result = array();
    private static $db_connection;


    public function __construct($config)
    {
        if (isset($config)) {
            $this->db_host = $config["host"];
            $this->db_user = $config["user"];
            $this->db_pass = $config["pass"];
            $this->db_name = $config["dbname"];

            $this->db_prefix = $config["prefix"];
        }
        $this->connect();
    }


    public function __destruct()
    {
        $this->disconnect();
    }


  /**
  * Initiate connection to database if not already connected
  * returns bool
  */
  public function connect()
  {
      if (!Database::$con) {
          $this->db_connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
      // set the PDO error mode to exception
      $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } else {
          return true;
      }
  }

  /**
  * Initiate connection to database if not already connected
  */
  public function disconnect()
  {
      if (Database::$con) {
          $this->db_connection = null;
          Database::$con = false;
          return true;
      } else {
          return false;
      }
  }

  /* SAMPLE EXPECTED DATA

  $columns = array(
    "col1",
    "col2",
    "col3",
  )

  $where = array(
    "col1 = 'something'",
    "col2 > 3",
  )

  $order = array(
    "col1 ASC",
    "col2 DESC",
  )

  **Does not acceot INNER JOIN at the moment**
  */
  public function selectStatement($table, $columns, $where = null, $order = null)
  {
      $table = $this->addPrefix($table);

      if (!$this->tableExists($table)) {
          return false;
      }

      $sql = "SELECT ";

      $first = true;
      foreach ($columns as $column) {
          if ($first) {
              $first = false;
              $sql .= $column;
          } else {
              $sql .= ", ";
              $sql .= $column;
          }
      }

      $sql .= " FROM ";
      $sql .= $table;

      if ($where) {
          $sql .= " WHERE ";

          $first = true;
          foreach ($where as $condition) {
              if ($first) {
                  $first = false;
                  $sql .= $condition;
              } else {
                  $sql .= " AND ";
                  $sql .= $condition;
              }
          }
      }

      if ($order) {
          $sql .= " ORDER BY ";

          $first = true;
          foreach ($order as $condition) {
              if ($first) {
                  $first = false;
                  $sql .= $condition;
              } else {
                  $sql .= ", ";
                  $sql .= $condition;
              }
          }
      }

      $statement = $this->db_connection->prepare($sql);
      $statement->execute();
      return $statement;
  }
  
  
    public function select($returnClass, $table, $columns, $where = null, $order = null)
    {
        $statement = $this->selectStatement($table, $columns, $where, $order);
    
        return $statement->fetchAll(PDO::FETCH_CLASS, $returnClass);
    }
  

    public function selectSingle($returnClass, $table, $columns, $where = null)
    {
        $statement = $this->selectStatement($table, $columns, $where);
    
        return $statement->fetchObject($returnClass);
    }


  /* SAMPLE EXPECTED DATA
  $data = [
    ['field' => "name", 'type' => "string", 'value' => "bob"],
    ['field' => "age", 'type' => "int", 'value' => "57"],
    ['field' => "gender", 'type' => "string", 'value' => "male"],
  ]

  REQUIRING DATA TYPES PROVIDES FLEXABILITY TO
  TRANSITION TO MYSQLI OR OTHER ANOTHER DRIVER
  EASILY IN THE FUTURE
  */
  public function insert($table, $data)
  {
      $table = $this->addPrefix($table);

      if (!$this->tableExists($table)) {
          return false;
      }

      $first = true;

      foreach ($data as $item) {
          if ($first) {
              $first = false;
              $fields .= $item["field"];
              $valuesPlaceholder .= ":" . $item["field"];
          } else {
              $fields .= ", " . $item["field"];
              $valuesPlaceholder .= ", :" . $item["field"];
          }

          if ($item["type"] == "s" || $item["type"] == "string") {
              $types .= "s";
          } elseif ($item["type"] == "i" || $item["type"] == "int" || $item["type"] == "integer") {
              $types .= "i";
          } elseif ($item["type"] == "bit" || $item["type"] == "bool" || $item["type"] == "boolean") {
              $types .= "i";
          } elseif ($item["type"] == "d" || $item["type"] == "double") {
              $types .= "d";
          } elseif ($item["type"] == "b" || $item["type"] == "blob") {
              $types .= "b";
          } else {
              return false;
          }
      }

      $statement = $this->db_connection->prepare("INSERT INTO ".$table." (" . $fields . ") VALUES (" . $valuesPlaceholder . ")");
      foreach ($data as $item) {
          $statement->bindParam(':'.$item["field"], $item["value"]);
      }

      if ($statement->execute()) {
          return true;
      } else {
          return false;
      }
  }


  /**
  *
  * Use with caution: it is often better to archive items to prevent creating null references.
  *
  */
  public function delete($table, $where)
  {
      $table = $this->addPrefix($table);

      if (!$this->tableExists($table)) {
          return false;
      }

      $sql = "DELETE FROM ";
      $sql .= $table;
      $sql .= " WHERE ";
      foreach ($where as $condition) {
          $sql .= $condition;
      }

      $statement = $this->db_connection->prepare($sql);

    // todo: restructure where clause and bind parameters

    if ($statement->execute()) {
        return true;
    } else {
        return false;
    }
  }


    public function update()
    {
    }


    private function addPrefix($table)
    {
        $table = $this->db_prefix . $table;
        return $table;
    }


    private function tableExists($table)
    {
        // todo: implement table check
    return true;
    }

    public function addQuotes($string)
    {
        return $this->db_connection->quote($string);
    }
  

    public function lastInsertId()
    {
        return $this->db_connection->lastInsertId();
    }


    public function count($table, $column, $where = null)
    {
        $columns = [
      "COUNT(".$column.") AS count",
     ];
        $statement = $this->selectStatement($table, $columns, $where);
        return $statement->fetchObject()->count;
    }
}
