<?php

namespace App\Controller;

use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ModelController extends AbstractController
{
    /**
     * @Route("/model/{id}", name="model")
     */
    public function index(ModelRepository $repo, $id): Response
    {
        $models = $repo->findBy(["brand" => $id]);
        return $this->json($models, 200,  [], ["groups" => 'model:index']);
    }
}
