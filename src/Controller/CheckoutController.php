<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    public function __construct(private CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    #[Route('/validation-commande', name: 'app_checkout')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartDetails();
        $cartItemCount = $this->cartService->getCartItemCount();
        if($cartItemCount === 0){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart'=> $cart,
        ]);
    }
}
