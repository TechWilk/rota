<?php

class Person
{
    private $id;
    private $email;
    public $firstName;
    public $lastName;

    public function setEmail(EmailAddress $email)
    {
        $this->email = $email;
    }

    public function createInDb(Database $db)
    {
        if (empty($this->name) || empty($this->datetime) || empty($this->series) || empty($this->type) || empty($this->sub_type) || empty($this->location)) {
            $message = 'Series name ('.$this->name.')';
            $message .= ' or date ('.$this->datetime.')';
            $message .= ' or series ('.$this->series.')';
            $message .= ' or type ('.$this->type.')';
            $message .= ' or sub_type ('.$this->sub_type.')';
            $message .= ' or location ('.$this->location.')';
            $message .= ' cannot be empty.';
            throw new Exception($message);
        }

        $currentTimestamp = $date = strftime('%F %T', time());

        $data = [
      ['field' => 'name', 'type' => 'string', 'value' => $this->name],
      ['field' => 'date', 'type' => 'string', 'value' => $this->datetime],
      ['field' => 'eventGroup', 'type' => 'int', 'value' => $this->series->getId()],
      ['field' => 'type', 'type' => 'int', 'value' => $this->type->getId()],
      ['field' => 'subType', 'type' => 'int', 'value' => $this->sub_type->getId()],
      ['field' => 'location', 'type' => 'int', 'value' => $this->location->getId()],
      ['field' => 'created', 'type' => 'datetime', 'value' => $currentTimestamp],
    ];

        if (isset($this->notes)) {
            $data[] = ['field' => 'comment', 'type' => 'string', 'value' => $this->note];
        }

        if (isset($this->bible_verse)) {
            $data[] = ['bibleVerse' => 'notes', 'type' => 'string', 'value' => $this->bible_verse];
        }

        $db->insert($this->db_table, $data);

        $this->id = $db->lastInsertId();

        return true;
    }
}
