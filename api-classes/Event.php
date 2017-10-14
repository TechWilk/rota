<?php

class Event
{
    protected $id;
    public $name;
    public $notes; //comment
    public $datetime;

    protected $sermon;

    protected $type;
    protected $typeId = null;

    protected $subType;
    protected $subTypeId = null;

    protected $location;
    protected $locationId = null;

    protected $createdBy;
    protected $created;
    protected $updated;

    protected $rehersals; // array of Rehersal

    protected $deleted = false;

    protected $db_table = 'events';

    public function getId()
    {
        return $this->id;
    }

    public function addSermon(Sermon $sermon)
    {
        $this->series = $series;
    }

    public function getSermon()
    {
        return $this->sermon;
    }

    public function setType(Type $eventType)
    {
        $this->eventType = $eventType;
    }

    public function getType()
    {
        if (!is_a($this->type, 'Type') && !empty($this->typeId)) {
            $this->type = new Type();
            $this->type->getFromDbWithId($typeId);

            return $this->type;
        } else {
            return;
        }
    }

    public function setSubType(SubType $subType)
    {
        $this->subType = $subType;
    }

    public function getSubType()
    {
        if (!is_a($this->eventType, 'SubType') && !empty($this->subTypeId)) {
            $this->subType = new SubType();
            $this->subType->getFromDbWithId($subTypeId);

            return $this->subType;
        } else {
            return;
        }
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        if (!is_a($this->location, 'Location') && !empty($this->locationId)) {
            $this->location = new Location();
            $this->location->getFromDbWithId($this->locationId);

            return $this->sublocationType;
        } else {
            return;
        }
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function addRehersals($rehersals)
    {
        foreach ($rehersals as $rehersal) {
            if (!is_a($rehersal, 'Rehersal')) {
                throw new Exception('Rehersal is not a Rehersal object: '.$rehersal, 1);
            }
        }
        $this->rehersals = $rehersals;
    }

    public function createInDb(Database $db)
    {
        if (empty($this->name) || empty($this->datetime) || empty($this->series) || empty($this->type) || empty($this->subType) || empty($this->location)) {
            $message = 'Series name ('.$this->name.')';
            $message .= ' or date ('.$this->datetime.')';
            $message .= ' or series ('.$this->series.')';
            $message .= ' or type ('.$this->type.')';
            $message .= ' or sub_type ('.$this->subType.')';
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
      ['field' => 'subType', 'type' => 'int', 'value' => $this->subType->getId()],
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

    public function getFromDbWithId(Database $db, $id = null)
    {
        if ($id == null) {
            $id = $this->id;
        } else {
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        }

        $columns = [
      'id',
      'name',
      'date',
      'eventGroup',
      'type',
      'subType',
      'location',
      'comment',
      'sermonTitle',
      'bibleVerse',
      'deleted',
      'created',
      'updated',
    ];

        $where = [
      'id = '.$id,
    ];

        $result = $db->selectSingle('Event', $this->db_table, $columns, $where);

        $this->id = $result->id;
        $this->name = $result->name;
        $this->datetime = $result->date;
        $this->deleted = $result->deleted;

        $this->notes = $result->comment;
        $this->bibleVerse = $result->bibleVerse;

        $series = new Series();
        $series->getFromDbWithId($db, $result->eventGroup);

        $sermon = new Sermon($result->sermonTitle, $result->comment, $result->bibleVerse, $series);
        $this->sermon = $sermon;

        $event_type = new Type();
        $event_type->getFromDbWithId($db, $result->type);
        $this->type = $event_type;

        $sub_type = new SubType();
        $sub_type->getFromDbWithId($db, $result->subType);
        $this->subType = $sub_type;

        $location = new Location();
        $location->getFromDbWithId($db, $result->location);
        $this->location = $location;

        $this->created = $result->created;
        $this->updated = $result->updated;

        return true;
    }
}
