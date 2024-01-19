<?php 
namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {
    private $session;
    

    public function __construct( private RequestStack $requestStack,private ProductRepository $productRepo )
    {       
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
        
    }

    public function getCart()
    {
        return $this->session->get("cart", []);
    }

    public function updateCart($cart)
    {
        // return $this->session->set("cart", $cart);
        $this->session->set("cart", $cart);
        return $cart;
    }

    public function addToCart($productId, $count = 1)
    {
         
        $cart = $this->getCart();

        if(!empty($cart[$productId])){
            // produit existe deja dans le panier
            $cart[$productId] += $count;
        }else{
            // produit n'existe pas dans le panier
            $cart[$productId] = $count;
        }

        $this->updateCart($cart);
    }

  
    public function removeToCart($productId, $count = 1)
{
    // On récupère le panier actuel
    $cart = $this->getCart();

    // On vérifie si le produit est déjà présent dans le panier
    if (isset($cart[$productId])) {
        // Si la quantité dans le panier est inférieure ou égale à la quantité à retirer alors
        if ($cart[$productId] <= $count) {
            // On supprime complètement le produit du panier
            unset($cart[$productId]);
        } else {
            // Sinon on réduit la quantité du produit dans le panier
            $cart[$productId] -= $count;
        }

        // apres on met à jour le panier 
        $this->updateCart($cart);
    }
}


    public function clearCart()
    {
        $this->updateCart([]);
    }

    public function getCartDetails()
    {
        
        $cart = $this->getCart();
        $result = [
            'items' => [],
            'sub_total' => 0
        ];

        $sub_total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepo->find($productId);
            if($product){
                $current_sub_total = $product->getPrice()*$quantity;
                $sub_total += $current_sub_total;
                $result['items'][] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    //'taxe' => 20,
                    'sub_total' => $product->getPrice()*$quantity,
                ];
                $result['sub_total'] = $sub_total;
            }else{
                unset($cart[$productId]);
                $this->updateCart($cart);
            }
        }
        
        return $result;
    }
    public function getCartItemCount()
    {
        $cart = $this->getCart();
        return array_sum($cart);
    }

}