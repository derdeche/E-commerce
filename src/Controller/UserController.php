<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
  
    #[Route('/mon-compte/{id}', name: 'app_user', methods:['GET', 'POST'])]
    public function profil(EntityManagerInterface $entityManager, $id, User $user, Request $request,OrderRepository $orderRepository, AddressRepository $addressRepository): Response
    {
        $repository = $entityManager->getRepository(User::class);
    
        // Utilisation de requête préparée pour éviter les injections SQL
        
        $query = $repository->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
    
        $user = $query->getOneOrNullResult();
    
        $addresses = $addressRepository->findBy(['user' => $user]);
    
        if (!$user) {
            throw $this->createNotFoundException('L\'Utilisateur n\'existe pas.');
        }
        
        
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();
        $id = $user->getId();
        $orders = $user->getOrders();
        
       
    
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'pseudo' => $pseudo,
            'email' => $email,
            'addresses' => $addresses,
            'id' => $id,
            'orders' => $orders,
        ]);
    }
    
    
    
    #[Route('/user/delete', name :'delete_user')]
    public function delete(ManagerRegistry $doctrine, Request $request, Security $security, OrderRepository $orderRepository): Response
{
    // on récupère l'utilisateur
    $user = $this->getUser();

    // on vérifie que l'utilisateur est connecté
    if ($user) {
        $entityManager = $doctrine->getManager();

        // récupérer les commandes de l'utilisateur
        $orders = $orderRepository->findBy(['user' => $user]);

        // on défini l'ID de chaque commande à zéro
        foreach ($orders as $order) {
            $order->setUserIdToZero();
            $entityManager->persist($order); // on assure de persister les changements
            
        }

        

        // Je supprime l'utilisateur de la BDD
        $entityManager->remove($user);
        $entityManager->flush();

        // J'invalide la session en cours
        $request->getSession()->invalidate();

        // Je déconnecte l'utilisateur et supprime son token de sécurité
        $this->container->get('security.token_storage')->setToken(null);

        $this->addFlash('success', 'Votre compte a été supprimé avec succès.');
        $this->addFlash('success-class', 'hide-message');

        // Je redirige l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }

    return $this->redirectToRoute('app_home');
}

 // une redirection vers la page home si un code est introduit dans
    /**
     * @Route("/{any}", name="redirect_home", requirements={"any"=".+"})
     */
    public function redirectHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
