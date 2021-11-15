<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\GarageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class GarageController extends AbstractController
{
    /**
     * @Route("/garage/all", name="garageAll")
     */
    public function index(GarageRepository $repo): Response
    {
        $garages = $repo->findAll();
        return $this->json($garages, 200, [], ['groups' => 'garage:index']);
    }

    /**
     * @Route("/api/garage/allByUser", name="garageByUser")
     */
    public function getAllByUser(User $user = null, UserInterface $currentUser, UserRepository $userRepository): Response{

        if(!$user){
            //$userUsername = $currentUser->getUserIdentifier();
            //$user = $userRepository->findOneBy(['username' => $userUsername]);
                $user = $currentUser;

        }
        $garages = $user->getGarages();
        return $this->json($garages, 200, [], ['groups' => 'garage:index']);
    }


    /**
     * @Route("garage/show/{id}", name="showGarage", requirements={"id"="\d+"})
     */
    public function show(Garage $garage){
        return $this->json($garage, 200, [], ['groups' => 'garage:index']);
    }

    /**
     * @Route("garage/delete/{id}", name="deleteGarage", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Garage $garage, EntityManagerInterface $manager){
        $manager->remove($garage);
        $manager->flush();
        return $this->json("ok");
    }

    /**
     * @Route("garage/new/{username}", name="newGarage", methods={"POST"})
     * @ParamConverter("gather", options={"mapping": {"address_rue"   : "rue"}})
     * @ParamConverter("gather", options={"mapping": {"address_cp"   : "addressCp"}})
     * @ParamConverter("gather", options={"mapping": {"address_city"   : "city"}})
     */

    public function create(User $user = null, UserRepository $userRepo, Request $requete, EntityManagerInterface $manager){

        $garage = new Garage();
        $info = $requete->toArray();

        if(!$user) {
            $user = $userRepo->find($info["user"]);
        }
        $garage -> setName($info['name']);
        $garage -> setPhone($info['phone']);
        $garage -> setAddressRue($info['addressRue']);
        $garage -> setAddressCp($info['addressCp']);
        $garage -> setAddressCity($info['addressCity']);
        $garage -> setUser($user);

        $manager->persist($garage);
        $manager->flush();
        return $this->json($garage, 200, [], ["groups" => 'garage:index']);

    }

    /**
     * @Route("garage/edit/{id}", name="editGarage", methods={"PATCH"}, requirements={"id"="\d+"})
     * @ParamConverter("gather", options={"mapping": {"address_rue"   : "rue"}})
     * @ParamConverter("gather", options={"mapping": {"address_cp"   : "addressCp"}})
     * @ParamConverter("gather", options={"mapping": {"address_city"   : "city"}})
     *
     */
    public function edit(Garage $garage, Request $requete, EntityManagerInterface $manager){
        $info = $requete->toArray();
        $garageEdit = $info;


        if($garageEdit['name'] != $garage->getName()){
            $garage->setName($garageEdit['name']);
        }

        if($garageEdit['phone'] != $garage->getPhone()){
            $garage->setPhone($garageEdit['phone']);
        }

        if($garageEdit['addressRue'] != $garage->getAddressRue()){
            $garage->setAddressRue($garageEdit['addressRue']);
        }

        if($garageEdit['addressCp'] != $garage->getAddressCp()){
            $garage->setAddressCp($garageEdit['addressCp']);
        }

        if($garageEdit['addressCity'] != $garage->getAddressCity()){
            $garage->setAddressCity($garageEdit['addressCity']);
        }

        $manager->persist($garage);
        $manager->flush();

        return $this->json($garage, 200, [], ["groups" => 'garage:index']);
    }
}
