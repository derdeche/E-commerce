<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderDetail;
use App\Services\CartService;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CheckoutController extends AbstractController
{
    private $em;
    private $session;

    public function __construct( private RequestStack $requestStack,
    private CartService $cartService,
    private EntityManagerInterface $entityManager,
    )
    {
        $this->session = $requestStack->getSession();
        $this->em = $entityManager;
        $this->entityManager = $entityManager;

    }
    
    #[Route('/validation-commande', name: 'app_checkout', methods: ['GET', 'POST'])]
    public function index(AddressRepository $addressRepository, Request $request): Response
    {
        $user = $this->getUser();
        $addresses = $addressRepository->findBy(['user' => $user]);

        $cart = $this->cartService->getCartDetails();
        $cartItemCount = $this->cartService->getCartItemCount();
        if($cartItemCount === 0){
            return $this->redirectToRoute('app_home');
        }
        if(!$user){
            $this->session->set("next", "app_checkout");
            return $this->redirectToRoute('app_login');
        }
        // dd($cart);
        
        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart'=> $cart,
            'addresses' => $addresses,
        ]);
        
    }


    #[Route('/confirmation-validation-commande', name: 'app_checkout-order', methods: ['POST'])]
    public function confirmOrder(Request $request): Response
    {
        if (!$request->isMethod('POST')) {
            return new Response('Méthode non autorisée', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    
        $livraisonSelected = $request->request->get("adresse-livraison");
        $cart = $this->cartService->getCartDetails();
    
        // on valide la commande
        $order = $this->createOrder($cart, $livraisonSelected);
    
        // on vide le panier après la validation de la commande
        $this->cartService->clearCart();
    
        // on obtient la quantité de chaque produit dans le panier
        $productQuantities = [];
        $productNames = []; // Nouvelle variable pour stocker les noms des produits
        $productPrices = $this->getProductPrices($order->getOrderDetails());


        foreach ($cart['items'] as $item) {
            $productId = $item['product']->getId();
            $quantity = $item['quantity'];
            $productQuantities[$productId] = $quantity;
            $productNames[$productId] = $item['product']->getName(); // ici on stocke le nom du produit
            

        }
        // on affiche la confirmation de la commande avec les quantités des produits
        return $this->render('checkout/confirmation_order.html.twig', [
            'orderId' => $order->getId(),
            'order' => $order,
            'productQuantities' => $productQuantities,
            'productNames' => $productNames, // on passe les noms des produits au modèle Twig
            'productPrices' => $productPrices, // on passe les prix des produits au modèle Twig


        ]);
    }


public function createOrder($cart, $livraisonSelected)
{
    if (isset($cart['items'])) {
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('En attente de validation');
        $order->setDeliveryAddress($livraisonSelected);
        $order->setTaxe(20);

        $orderCost = 0; // Initialisez le coût total de la commande

        foreach ($cart['items'] as $item) {
            $productId = $item['product']->getId();
            $product = $this->entityManager->getRepository(Product::class)->find($productId);

            // on crée et ajoutez un nouvel OrderDetail à la commande
            $orderDetail = new OrderDetail();
            $orderDetail->setOrder($order);
            $orderDetail->setProduct($product);
            $orderDetail->setQuantity($item['quantity']);

            // on ajoute le coût du produit multiplié par la quantité à orderCost
            $orderCost += ($product->getPrice() * $item['quantity']);

            $order->addOrderDetail($orderDetail);
        }

        $order->setOrderCost($orderCost); // on met à jour le coût total de la commande

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}


private function getProductPrices($orderDetails)
{
    $productPrices = [];
    
    foreach ($orderDetails as $orderDetail) {
        $productId = $orderDetail->getProduct()->getId();
        $productPrices[$productId] = $orderDetail->getProduct()->getPrice();
    }
    
    return $productPrices;
}




#[Route('/details-de-la-commande/{id}', name: 'app_order_details', methods: ['GET'])]
public function orderDetails($id): Response
{
    $order = $this->entityManager->getRepository(Order::class)->find($id);

    if (!$order) {
        throw $this->createNotFoundException('Commande non trouvée');
    }

    $orderDetails = $order->getOrderDetails();

    $productQuantities = [];

    foreach ($orderDetails as $orderDetail) {
        $product = $orderDetail->getProduct();
        $quantity = $orderDetail->getQuantity();
        $productId = $product->getId();
        $productPrice = $product->getPrice(); // on récupére le prix unitaire du produit

        $productQuantities[$productId] = [
            'quantity' => $quantity,
            'price' => $productPrice,
        ];
    }

    return $this->render('checkout/order_details.html.twig', [
        'orderId' => $order->getId(),
        'order' => $order,
        'orderDetails' => $orderDetails,
        'productQuantities' => $productQuantities,
    ]);
}

    

 
    





}



