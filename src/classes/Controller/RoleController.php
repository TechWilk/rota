<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\RoleQuery;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\UserRole;
use TechWilk\Rota\UserRoleQuery;

class RoleController extends BaseController
{
    public function getAssignRolesForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/roles'");
        $r = RoleQuery::create()->find();
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-roles-assign.twig', ['user' => $u, 'roles' => $r]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function postUserAssignRoles(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create user people POST '/user/".$args['id']."/roles'");

        $userId = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
        $existingRoles = RoleQuery::create()->useUserRoleQuery()->filterByUserId($userId)->endUse()->find();

        $existingRoleIds = [];
        foreach ($existingRoles as $r) {
            $existingRoleIds[] = $r->getId();
        }

        $data = $request->getParsedBody();

        if (empty($data['role']) || !is_array($data['role'])) {
            // delete all roles
            $urs = UserRoleQuery::create()->filterByUserId($userId)->find();
            foreach ($urs as $ur) {
                $ur->delete();
            }
        } else {
            // sanitize data from user
            foreach ($data['role'] as $key => $role) {
                $data['role'][$key] = filter_var(trim($role), FILTER_SANITIZE_NUMBER_INT);
            }

            // add new roles
            $addArray = array_diff($data['role'], $existingRoleIds);
            foreach ($addArray as $roleToAdd) {
                $ur = new UserRole();
                $ur->setRoleId($roleToAdd);
                $ur->setUserId($userId);
                $ur->save();
            }

            // remove existing roles
            $deleteArray = array_diff($existingRoleIds, $data['role']);
            foreach ($deleteArray as $roleToRemove) {
                $ur = UserRoleQuery::create()->filterByUserId($userId)->filterByRoleId($roleToRemove)->findOne();
                $ur->delete();
            }
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', ['id' => $userId]));
    }
}
