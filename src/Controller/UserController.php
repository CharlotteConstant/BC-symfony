<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $requete, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {

        $user = new User();

        $info = $requete->toArray();
        $user -> setFirstname($info['firstname']);
        $user -> setLastname($info['lastname']);
        $user -> setEmail($info['email']);

$hashedPassword = $hasher->hashPassword($user, $info['password']);
$user->setPassword($hashedPassword);
$user -> setSiret($info['siret']);
$user -> setPhone($info['phone']);

$user -> setUsername($info['username']);

$manager->persist($user);
$manager->flush();

        return $this->json($user, 200);

    }

    /**
     * @Route("/user/edit/{id}", name="editUser", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(User $user, EntityManagerInterface $manager, Request $requete){

    $info = $requete->toArray();
    $user -> setFirstname($info['firstname']);
    $user -> setLastname($info['lastname']);
    $user -> setEmail($info['email']);
    $user -> setSiret($info['siret']);
    $user -> setPhone($info['phone']);


        $manager->persist($user);
        $manager->flush();

        return $this->json($user, 200, [], ["groups" => 'user:index']);
    }


    /**
     * @Route("/user/logout", name="logout")
     */
    public function logout(){

    }

    /**
     * @route("api/user/all", name="userAll")
     */
    public function findAllUsers(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->json($users, 200,  [], ["groups" => 'user:index']);
    }

    /**
     * @Route("api/user/show", name="userShow")
     */
    public function show(UserInterface $currentUser, User $user = null): Response
    {
        if(!$user){
            $user = $currentUser;
        }
        return $this->json($user, 200, [], ['groups' => 'user:index']);
    }

    /**
     * @Route("user/delete/{id}", name="deleteUser", methods={"DELETE"}, requirements={"id"="\d+"})
     **/
    public function deleteUser(User $user, EntityManagerInterface $manager): Response{
$manager->remove($user);
$manager->flush();
return $this->json("ok supprime");
    }
}
