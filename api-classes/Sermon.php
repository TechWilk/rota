<?php

class Sermon
{
    protected $id; // not used
  public $name;
    public $notes; //comment
  public $bible_verse;
    protected $series; //eventGroup

 public function __construct($name, $notes, $bible_verse, Series $series)
 {
     $this->name = $name;
     $this->notes = $notes;
     $this->bible_verse = $bible_verse;
     $this->series = $series;
 }

    public function getSeries()
    {
        return $this->series;
    }
}
