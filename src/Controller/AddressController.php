<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte/adresse')]
class AddressController extends AbstractController
{
    #[Route('/', name: 'app_address_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();
        $addresses = $addressRepository->findBy(['user' => $user]);
        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    #[Route('/ajouter', name: 'app_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
        $user = $this->getUser();
    
        if (!$user instanceof User) {
          
            return $this->redirectToRoute('app_error');
        }
    
        $address->setUser($user);
    
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
    
            // Après avoir persisté l'adresse, on récupére l'ID de l'utilisateur
            $userId = $user->getId();

            $this->addFlash('success', 'Votre Adresse a été ajoutée avec succès.');
            $this->addFlash('success-class', 'hide-message');

            // Redirection vers la page app_user avec l'ID de l'utilisateur
            return $this->redirectToRoute('app_user', ['id' => $userId], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }
    
    

    #[Route('/{id}', name: 'app_address_show', methods: ['GET'])]
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

  

    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        $userId = $address->getUser()->getId(); // on récupére l'ID de l'utilisateur avant la suppression
    
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Votre Adresse a été supprimée avec succès.');
        $this->addFlash('success-class', 'hide-message');
        // on redirige vers la page app_user avec l'ID de l'utilisateur
        return $this->redirectToRoute('app_user', ['id' => $userId], Response::HTTP_SEE_OTHER);
    }

    
     // une redirection vers la page home si un code est introduit dans
    /**
     * @Route("{any}", name="redirect_home", requirements={"any"=".+"})
     */
    public function redirectHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
