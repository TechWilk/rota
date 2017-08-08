<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Stream;
use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\RoleQuery;
use TechWilk\Rota\DocumentQuery;
use TechWilk\Rota\Document;
use TechWilk\Rota\EmailAddress;
use Exception;

class ResourceController extends BaseController
{

    public function getAllResources(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch resource GET '/resources'");
        $resources = DocumentQuery::create()->orderByTitle()->find();

        return $this->view->render($response, 'resources.twig', [ "resources" => $resources ]);
    }

    public function postResource(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create resource POST '/resource'");

        $data = $request->getParsedBody();

        $data['title'] = filter_var(trim($data['title']), FILTER_SANITIZE_STRING);
        $data['description'] = filter_var(trim($data['description']), FILTER_SANITIZE_STRING);

        $d = new Document();

        if (isset($args['id'])) {
            $d = DocumentQuery::create()->findPK($args['id']);
        }

        $d->setTitle($data['title']);
        $d->setDescription($data['description']);
        $d->setLink(''); //todo: fix defaults

        if (!isset($args['id'])) {
            try {
                $files = $request->getUploadedFiles();
                $d->saveFile($files['file']);
            } catch (Exception $e) {
                return $this->view->render($response, 'resource-edit.twig', ['resource' => $d, 'error' => $e]);
            }
        }

        $d->save();

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('resources'));
    }

    public function getNewResourceForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch resource GET '/resource/new'");

        return $this->view->render($response, 'resource-edit.twig');
    }

    public function getResourceEditForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch resource GET '/resource/".$args['id']."/edit'");
        $d = DocumentQuery::create()->findPK($args['id']);

        if (!is_null($d)) {
            return $this->view->render($response, 'resource-edit.twig', [ "resource" => $d ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getResourceFile(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch resource GET '/resource/".$args['id']."'");
        $resource = DocumentQuery::create()->findPk($args['id']);

        if (!is_null($resource)) {
            if (file_exists(__DIR__.'/../documents/'.$resource->getUrl())) {
                $file = __DIR__.'/../documents/'.$resource->getUrl();
            } elseif (file_exists(__DIR__.'/../documents/'.$resource->getId())) {
                $file = __DIR__.'/../documents/'.$resource->getId();
            } else {
                return $this->view->render($response, 'error.twig');
            }

            $fh = fopen($file, 'rb');

            $stream = new Stream($fh); // create a stream instance for the response body

            return $response->withHeader('Content-Type', 'application/force-download')
                            ->withHeader('Content-Type', 'application/octet-stream')
                            ->withHeader('Content-Type', 'application/download')
                            ->withHeader('Content-Description', 'File Transfer')
                            ->withHeader('Content-Transfer-Encoding', 'binary')
                            ->withHeader('Content-Disposition', 'attachment; filename="' . $resource->getUrl() . '"')
                            ->withHeader('Expires', '0')
                            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                            ->withHeader('Pragma', 'public')
                            ->withBody($stream); // all stream contents will be sent to the response
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }
}
