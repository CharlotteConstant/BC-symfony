<?php

namespace App\Controller;

use App\Repository\FuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class FuelController extends AbstractController
{
    /**
     * @Route("/fuel", name="fuel")
     */
    public function index(FuelRepository $repo): Response
    {
        $fuels = $repo->findAll();
        return $this->json($fuels, 200,  [], ["groups" => 'fuel:index']);
    }
}
