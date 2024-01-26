<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    // #[Route('/profil/{id}', name: 'app_user', methods: ['GET', 'POST'])]
    // public function index(int $id, EntityManagerInterface $entityManager): Response
    // {
    //     $user = $entityManager->getRepository(User::class)->find($id);

    //     if (!$user) {
    //         throw $this->createNotFoundException('Utilisateur non trouvé');
    //     }

    //     return $this->render('user/index.html.twig', [
    //         'user' => $user,
    //     ]);
    // }

    
    #[Route('/mon-compte/{id}', name: 'app_user', methods:['GET', 'POST'])]
    public function profil(EntityManagerInterface $entityManager, $id, User $user, Request $request, AddressRepository $addressRepository): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $addresses = $addressRepository->findBy(['user' => $user]);

        if (!$user) {
            throw $this->createNotFoundException('L\'Utilisateur n\' existe pas.');
        }


            $pseudo = $user->getPseudo();
            $email = $user->getEmail();
            $id=$user->getId();

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'pseudo' => $pseudo,
            'email' => $email,
            'addresses' => $addresses,
            'id' => $id,


        ]);
    }

    #[Route('/user/delete', name :'delete_user')]
    public function delete(ManagerRegistry $doctrine, Request $request, Security $security): Response
   {
       // on recupere l'utilisateur
       $user = $this->getUser();

       // on verifie que l'utilisateur est connecté
       if($user){
           $entityManager = $doctrine->getManager();
      

           // Je supprimer l'utilisateur de la BDD
           $entityManager ->remove($user);
           $entityManager ->flush();

           // J'invalide la session en cours
           $request->getSession()->invalidate();

           // Je deconnecte l'utilisateur et supprimer son token de securité
           $this->container->get('security.token_storage')->setToken(null);

           $this->addFlash('success', 'Votre compte a été supprimé avec succès.');
           $this->addFlash('success-class', 'hide-message');
           
           // Je redirige l'utilisateur vers la page d'acceuil
           return $this->redirectToRoute('app_home');
       }
       return $this->redirectToRoute('app_home');

   }
}
