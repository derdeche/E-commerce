<?php

namespace App\Controller;

use App\Services\CartService;
use App\Repository\PageRepository;
use App\Repository\SliderRepository;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $repoProduct;
    private $cartService;

    public function __construct(ProductRepository $repoProduct,CartService $cartService){
        $this->repoProduct = $repoProduct;
        $this->cartService = $cartService;
      
        

    }

    #[Route('/home', name: 'app_home')]
    public function index(
        SliderRepository $sliderRepo,
        PageRepository $pageRepo,
        ProductRepository $productRepo,
        Request $request,
        CategoryRepository $categoryRepo,
        

        ): Response
    {
        $session = $request->getSession();
        $sliders = $sliderRepo->findAll();      
        $category = $categoryRepo->findAll();
        // $products = $productRepo->findAll();

        $productsBestSeller = $productRepo->findBy(['isBestSeller'=>true]);
        $productsSpecialOffer = $productRepo->findBy(['isSpecialOffer'=>true]);
        $productsNewCollection = $productRepo->findBy(['isNewCollection'=>true]);
        $productsPreference = $productRepo->findBy(['isPreference'=>true]);
        $footerPages = $pageRepo->findBy(['isFoot'=>true]); 
        $cart = $this->cartService->getCart();
        $session->set('footerPages',$footerPages);
       
        
        return $this->render('home/index.html.twig', [
            'sliders' => $sliders,
            'productsBestSeller' => $productsBestSeller,
            'productsSpecialOffer' => $productsSpecialOffer,
            'productsNewCollection' => $productsNewCollection,
            'productsPreference' => $productsPreference,
            // 'product' => $products,
            'categories' => $category,
            'cart' => $cart,
        ]);
    }
    
//     /**
//      * @Route("/produits-de-la-categorie/{categoryId}", name="products_by_category")
//      */
//     public function productsByCategory(
//         CategoryRepository $categoryRepo,
//         $categoryId
//     ): Response {
//         // on récupére la catégorie par son id
//         $category = $categoryRepo->find($categoryId);

//         // on récupére les produits de la catégorie
//         $products = $category->getProducts();

//         return $this->render('home/products_by_category.html.twig', [
//             'categoryId' => $categoryId,
//             'category' => $category,
//             'product' => $products,
//         ]);
// }

// ***********************************************
// /**
//  * @Route("/produits-de-la-categorie/{categoryId}", name="products_by_category")
//  */
// public function productsByCategory(
//     CategoryRepository $categoryRepo,
//     ProductRepository $productRepo,
//     Request $request,
//     $categoryId
// ): Response {
//     // on récupère la catégorie par son id
//     $category = $categoryRepo->find($categoryId);

//     // on récupère les produits de la catégorie
//     $products = $category->getProducts()->toArray();

//     // Récupérer le paramètre de filtre de prix
//     $prixMax = $request->query->get('prix_max');

//     // Appliquer le filtre de prix si le paramètre est défini
//     if ($prixMax !== null) {
//         $products = array_filter($products, function ($product) use ($prixMax) {
//             return $product->getPrix() < $prixMax;
//         });
//     }

//     return $this->render('home/products_by_category.html.twig', [
//         'categoryId' => $categoryId,
//         'category' => $category,
//         'products' => $products,
//     ]);
// }

// *****************************************************    
// /**
//  * @Route("/produits-de-la-categorie/{categoryId}", name="products_by_category")
//  */
// public function productsByCategory(
//     CategoryRepository $categoryRepo,
//     ProductRepository $productRepo,
//     Request $request,
//     $categoryId
// ): Response {
//     // on récupère la catégorie par son id
//     $category = $categoryRepo->find($categoryId);

//     // on récupère les produits de la catégorie
//     $products = $category->getProducts()->toArray();

//     // on récupére le paramètre de filtre de prix
//     $prixMax = $request->query->get('prix_max');
    

//     // ici on applique le filtre de prix si le paramètre est défini
//     if ($prixMax !== null) {
//         $products = array_filter($products, function ($product) use ($prixMax) {
//             return $product->getPrix() < $prixMax;
//         });
//     }
//     // ici on récupère le paramètre de filtre de prix

//     $prixMax = $request->query->get('prix_filter');

//     // ici on récupére le paramètre de tri
//     $sortBy = $request->query->get('sort_by');

//     // ici on applique le tri si le paramètre est défini
//     if ($sortBy === 'asc') {
//         usort($products, function ($a, $b) {
//             return $a->getPrice() - $b->getPrice();
//         });
//     } elseif ($sortBy === 'desc') {
//         usort($products, function ($a, $b) {
//             return $b->getPrice() - $a->getPrice();
//         }); 
//     }

//     return $this->render('home/products_by_category.html.twig', [
//         'categoryId' => $categoryId,
//         'category' => $category,
//         'products' => $products,
//     ]);
// }





  /**
     * @Route("/produits-de-la-categorie/{categoryId}", name="products_by_category")
     */
    public function productsByCategory(
        CategoryRepository $categoryRepo,
        ProductRepository $productRepo,
        Request $request,
        $categoryId
    ): Response {
        // on récupère la catégorie par son id
        $category = $categoryRepo->find($categoryId);

        // on récupère les produits de la catégorie
        $products = $category->getProducts()->toArray();

        // on récupère le paramètre de filtre de prix
        $prixMax = $request->query->get('prix_max');

        // ici on applique le filtre de prix si le paramètre est défini
        if ($prixMax !== null) {
            $products = array_filter($products, function ($product) use ($prixMax) {
                return $product->getPrice() < $prixMax;
            });
        }

        // ici on récupère le paramètre de tri
        $sortBy = $request->query->get('sort_by');

        // ici on applique le tri si le paramètre est défini
        if ($sortBy === 'asc') {
            usort($products, function ($a, $b) {
                return $a->getPrice() - $b->getPrice();
            });
        } elseif ($sortBy === 'desc') {
            usort($products, function ($a, $b) {
                return $b->getPrice() - $a->getPrice();
            });
        }

        return $this->render('home/products_by_category.html.twig', [
            'categoryId' => $categoryId,
            'category' => $category,
            'products' => $products,
        ]);
    }

// // ***********************************************




}
