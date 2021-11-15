<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Fuel;
use App\Entity\Garage;
use App\Entity\Model;
use App\Entity\Photo;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\BrandRepository;
use App\Repository\FuelRepository;
use App\Repository\GarageRepository;
use App\Repository\ModelRepository;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AdController extends AbstractController
{

    /**
     * @Route("/", name="ads")
     */
    public function index(AdRepository $repo): Response
    {
        /*
       mrTableau = serializer6>desialize($requete->getContent)
        $mrTableau = [
                        "annee"=>1999]*/


        $ads = $repo->findall(/*$mrTableau*/);


        return $this->json($ads, 200, [], ["groups" => 'ad:index']);
    }

    /**
     * @Route("/ads/byUser/{id}", name="annoncesByUserId", requirements={"id"="\d+"})
     * @Route("/ads/allByUser/{username}", name="annoncesByUser")
     */
    public function getAnnoncesByUser(User $user=null, UserRepository $userRepository): Response
    {

        if(!$user){
      $user = $userRepository->findUserByUsername("username");
        }

        $annonces = $user->getAds();


        return $this->json($annonces, 200, [], ["groups" => 'ad:index']);
    }

    /**
     * @Route("/ad/{id}", name="showAd", requirements={"id"="\d+"})
     *
     */
    public function show(Ad $ad){
       return $this->json($ad, 200, [], ["groups" => 'ad:index']);
    }

    /**
     * @Route("/ad/delete/{id}", name="deleteAd", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $manager, Ad $ad)
    {
        $manager->remove($ad);
        $manager->flush();

        return $this->json("bien supprime", 200);
    }

    /**
     * @Route("/ad/new/{username}", name="newAd", methods={"POST"})
     * @Route("/ad/edit/{id}", name="editAd", requirements={"id"="\d+"})
     */
    public function create(Ad $ad=null, User $user=null, GarageRepository $garageRepository, UserRepository $userRepository, FuelRepository $fuelRepo, ModelRepository $modelRepo, Request $requete, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        if(!$ad) {
            $ad = new Ad();
        }
        $annonce = $requete->toArray();

  /* $ad= $serializer->deserialize($requete->getContent(), Ad::class, 'json');*/

        if(!$user) {
            $user = $userRepository->find($annonce["user"]);
        }
        $fuel = $fuelRepo->find($annonce["fuel"]);
        $model = $modelRepo->find($annonce["model"]);
        $garage = $garageRepository->find($annonce["garage"]);
        //$user = $userRepository->find($annonce["user"]);

        $ad->setTitle($annonce["title"]);
        $ad->setDescription($annonce["description"]);
        $ad->setYear(new \DateTime($annonce["year"]));
        $ad->setKilometers($annonce['kilometers']);
        $ad->setPrice($annonce['price']);
        $ad->setFuel($fuel);
        $ad->setModel($model);
        $ad->setGarage($garage);
        $ad->setUser($user);
       //if(empty($ad->getPhotos())){
        //    $ad->addPhoto($annonce["photos"]);
       // }
        //$photo = new Photo();
        //$photo->setPath($annonce["photos"]);
        //$photo->setAd($ad);

    $manager->persist($ad);
    //$manager->persist($photo);
    $manager->flush();
    return $this->json($ad, 200, [], ["groups" => 'ad:index']);
    }



    /**
     * @Route("ad/edit/{id}", name="editAnnonce", methods={"PATCH"}, requirements={"id"="\d+"})
     *
     */
    public function edit(Fuel $fuel, FuelRepository $fuelRepository,Ad $ad, Request $requete,UserRepository $userRepository, EntityManagerInterface $manager){
        $infoAd = $requete->toArray();
        $adEdit = $infoAd;
        //$user = $userRepository->find($adEdit["user"]);
        //$fuel = $fuelRepository->find($adEdit["fuel"]);

        if($adEdit['title'] != $ad->getTitle()){
            $ad->setTitle($adEdit['title']);
        }
        if($adEdit['description'] != $ad->getDescription()){
            $ad->setDescription($adEdit['description']);
        }
        if($adEdit['year'] != $ad->getYear()){
            $ad->setYear($adEdit['year']);
        }
        if($adEdit['kilometers'] != $ad->getKilometers()){
            $ad->setKilometers($adEdit['kilometers']);
        }
        if($adEdit['price'] != $ad->getPrice()){
            $ad->setPrice($adEdit['price']);
        }
        /*
                    if($adEdit['fuel'] != $ad->getFuel()){
                        $ad->setFuel($adEdit['fuel']);
                    }

                           if($adEdit['model'] != $ad->getModel()){
                               $ad->setModel($adEdit['model']);
                           }
                           if($adEdit['garage'] != $ad->getGarage()){
                               $ad->setGarage($adEdit['garage']);
                           }*/

          //$ad->setUser($adEdit['user']);

        //$ad->setFuel($fuel);
        //$ad->setModel($adEdit['model']);
       // $ad->setGarage($adEdit['garage']);

        $manager->persist($ad);
        $manager->flush();

        return $this->json($ad, 200, [], ["groups" => 'ad:index']);
    }

    /**
     * @Route("ad/search", name="searchAd", methods={"POST"})
     */
    public function search(Request $request, AdRepository $adRepository): Response{
        $dataSearch = $request->toArray();
        $ads = $adRepository->findAdBySelection($dataSearch['brand'], $dataSearch['model'], $dataSearch['fuel'],
         $dataSearch['kilometers'], $dataSearch['year'], $dataSearch['price']);

        return $this->json($ads, 200, [], ["groups" => 'ad:index']);
    }

}
