<?php

namespace TechWilk\Rota;

use DateTime;

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

    public function getConfig()
    {
        return getConfig();
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

            $this->menu['groups'] = GroupQuery::create()
                                        ->distinct()
                                        ->find();
        }

        return $this->menu;
    }

    public function getUrl()
    {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];

        return [
            'base'     => $http.'://'.$hostname,
            'protocol' => $http,
            'host'     => $hostname,
        ];
    }
}
