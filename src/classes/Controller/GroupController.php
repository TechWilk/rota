<?php

namespace TechWilk\Rota\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\EventQuery;
use TechWilk\Rota\GroupQuery;

class GroupController extends BaseController
{
    public function getGroup(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch group GET '/group/".$args['id']."'");
        $events = EventQuery::create()
            ->useEventPersonQuery()
                ->useUserRoleQuery()
                    ->useRoleQuery()
                        ->filterByGroupId($args['id'])
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByDate(['min' => new DateTime()])
            ->filterByRemoved(false)
            ->orderByDate('asc')
            ->distinct()
            ->find();

        $group = GroupQuery::create()
                ->useRoleQuery()
                ->endUse()
                ->distinct()
                ->findPk($args['id']);

        return $this->view->render($response, 'group.twig', ['events' => $events, 'group' => $group]);
    }

    public function getGroupRoles(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch group roles GET '/group/".$args['id']."/roles'");
        $events = EventQuery::create()
            ->useEventPersonQuery()
                ->useUserRoleQuery()
                    ->useRoleQuery()
                        ->filterByGroupId($args['id'])
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByDate(['min' => new DateTime()])
            ->filterByRemoved(false)
            ->orderByDate('asc')
            ->distinct()
            ->find();

        $group = GroupQuery::create()
                ->useRoleQuery()
                ->endUse()
                ->distinct()
                ->findPk($args['id']);

        // temporary redirect
        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home').'old/roles.php');

        return $this->view->render($response, 'group-roles.twig', ['events' => $events, 'group' => $group]);
    }
}
