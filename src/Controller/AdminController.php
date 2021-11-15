<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\GarageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("admin/allGarages", name="allGarages")
     */
    public function allGarages(GarageRepository $garageRepository)
    {
        $garages = $garageRepository->findAll();
        return $this->json($garages, 200, [], ['groups' => 'adminGarage']);
    }

    /**
     * @Route("admin/allAds", name="allAds")
     */
    public function allAds(AdRepository $adRepository)
    {
        $ads = $adRepository->findAll();
        return $this->json($ads, 200, [], ['groups' => 'adminAds']);
    }

    /**
     * @Route("admin/allUsers", name="allUsers")
     */
    public function allUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->json($users, 200, [], ['groups' => 'user:index']);
    }

    /**
     * @Route("admin/stats", name="stats")
     */
    public function stats(GarageRepository $garageRepository, AdRepository $adRepository, UserRepository $userRepository){
        $totalGarages = count($garageRepository->findAll());
        $totalAds = count($adRepository->findAll());
        $totalUsers = count($userRepository->findAll());
        $stats = ['totalGarages' => $totalGarages, 'totalAds' => $totalAds, 'totalUsers' => $totalUsers];
        return $this->json($stats);
    }

}
