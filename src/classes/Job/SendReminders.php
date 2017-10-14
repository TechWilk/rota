<?php

namespace TechWilk\Rota\Job;

use TechWilk\Rota\Base\Settings;
use TechWilk\Rota\EmailSender;

class SendReminders
{
    private $emailSender;
    private $options;
    private $settings;
    private $userToBeNotified;
    private $dateFilter;

    public function __construct(EmailSender $emailSender)
    {
        $this->settings = SettingsQuery::create()->findOne();

        if ($this->settings->getDaysToAlert() <= 0) {
            throw new Exception('Reminders disabled in settings. Set daysToAlert above 0 to enable.');
        }

        $this->emailSender = $emailSender;
        $this->options = $options;
    }

    public function sendReminders()
    {
        $this->dateFilter = [
            'min' => new DateTime(),
            'max' => new DateTime('+ '.$this->settings->getDaysToAlert().' days'),
        ];

        $this->userToBeNotified = User::create()
            ->useUserRoleQuery()
                ->useEventPersonQuery()
                    ->filterByNotified(false)
                    ->useEventQuery()
                        ->filterByDate($this->dateFilter)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        foreach ($this->userToBeNotified as $user) {
            $this->sendEmailReminderToUser($user);

            $this->sendFacebookReminderToUser($user);
        }
    }

    private function sendEmailReminderToUser($user)
    {
        $events = EventQuery::create()
            ->filterByDate($this->dateFilter)
            ->useEventPersonQuery()
                ->useUserRoleQuery()
                    ->filterByUser($user)
                ->endUse()
            ->endUse()
            ->find();

        foreach ($events as $event) {
            $eventRoles = EventPersonQuery::create()
                ->filterByEvent($event)
                ->useUserRoleQuery()
                    ->filterByUser($user)
                ->endUse()
                ->find();

            $rolesForUser = [];

            foreach ($eventRoles as $eventRole) {
                $rolesForUser[] = $eventRole->getUserRole();
            }

            $emails[] = null;
        }

        // build email

        // send
    }

    private function sendFacebookReminderToUser($user)
    {
        return false;
    }
}
