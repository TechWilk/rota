<?php

class Site
{
    private $settings;
    private $menu;

    public function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = SettingsQuery::create()->findOne();
        }
        return $this->settings;
    }

    public function getMenu()
    {
        if (!isset($this->menu)) {
            $this->menu['events']['type'] = EventTypeQuery::create()
                                        ->useEventQuery()
                                          ->filterByRemoved(false)
                                          ->filterByDate(['min' => new DateTime()])
                                        ->endUse()
                                        ->distinct()
                                        ->find();
        }
        return $this->menu;
    }
}
