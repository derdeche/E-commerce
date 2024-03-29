<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(private CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
   
    #[Route('/panier', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartDetails();
        $cartItemCount = $this->cartService->getCartItemCount();
        // dd($cart);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'cartItemCount' => $cartItemCount,
            
            
        ]);
    }
       // une redirection vers la page home si un code est introduit dans
    /**
     * @Route("{any}", name="redirect_home", requirements={"any"=".+"})
     */
    public function redirectHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
    
    

   
    #[Route('/panier/ajout/{productId}/{count}', name: 'app_add_to_cart')]
    public function addToCart(Request $request, string $productId, $count = 1): Response
    {
        $this->cartService->addToCart($productId, $count);
        
        // $this->addFlash('success', 'Le produit a été ajoutée avec succès.');
        //     $this->addFlash('success-class', 'hide-message');

        // Récupérer l'URL de la page précédente
        $referer = $request->headers->get('referer');

        // Si l'URL  est disponible, on utilise cette URL pour la redirection
        if ($referer) {
            return $this->redirect($referer);
        }
      
        // Sinon, rediriger vers une URL par défaut 
        return $this->redirectToRoute('app_cart');
    }
    
    // ***********************************************
    #[Route('/panier/nombre', name: 'app_cart_count', methods: ['GET'])]
    public function getCartItemCount(): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $totalItemCount = array_sum($cart);

        return $this->json(['count' => $totalItemCount]);
    }
   

    // *************************************
    
    #[Route('/panier/suppression/{productId}/{count}', name: 'app_remove_to_cart')]
    public function removeToCart(string $productId, $count = 1): Response
    {
        $this->cartService->removeToCart($productId,$count);
        return $this->redirectToRoute("app_cart");
        
    }

}
