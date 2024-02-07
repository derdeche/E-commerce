<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\QuantityType;
use App\Services\CartService;

use App\Repository\PageRepository;
use App\Repository\SliderRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController

{
    private $repoProduct;
    private $cartService;
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager, ProductRepository $repoProduct,CartService $cartService){
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;

        
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
            'categories' => $category,
            'cart' => $cart,
        ]);
    }
    
    
    #[Route("products-for-slider/{slider_id}", name:"products_for_slider")]     
    public function productsForSlider(    $slider_id,    SliderRepository $sliderRepo,
    ProductRepository $productRepo): Response
    {
        $sliders = $sliderRepo->findAll();      

        // $products = $productRepo->findBy(['slider' => $sliderId]);
        $productsBestSeller = $productRepo->findBy(['isBestSeller'=>true]);
        $productsSpecialOffer = $productRepo->findBy(['isSpecialOffer'=>true]);
        $productsNewCollection = $productRepo->findBy(['isNewCollection'=>true]);

        return $this->render('home/products_for_slider.html.twig', [
            // 'products' => $products,
            'sliderId' => $slider_id, 
            'sliders' => $sliders,
            'productsSpecialOffer' => $productsSpecialOffer,
            'productsNewCollection' => $productsNewCollection,
            'productsBestSeller' => $productsBestSeller,
        ]);
    }
  

    #[Route("/produits-de-la-categorie/{categoryId}", name: "products_by_category")]
    public function productsByCategory(
        CategoryRepository $categoryRepo,
        Request $request,
        $categoryId
    ): Response {
        // on récupère la catégorie par son id
        $category = $categoryRepo->find($categoryId);

        // Vérifiez si la catégorie existe
        if (!$category) {
            // Si la catégorie n'existe pas, redirigez vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // on récupère les produits de la catégorie
        $products = $category->getProducts()->toArray();

        // on récupère le paramètre de tri
        $sortBy = $request->query->get('sort_by');

        // on applique le tri si le paramètre est défini
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
            'product' => $products,
        ]);
    }

        
        
        
        #[Route("/produit/{slug}", name:"app_product_by_slug")]
        
        public function showProductBySlug(Request $request, string $slug): Response
        {
            // Récupération du repository des produits
            $productRepository = $this->entityManager->getRepository(Product::class);

            // Recherche du produit par le slug dans la base de données
            $product = $productRepository->findOneBy(['slug' => $slug]);

            // Si le produit n'est pas trouvé, redirige vers la page d'accueil
            if (!$product) {
                return $this->redirectToRoute('app_home');
            }

            // Création d'un formulaire pour la gestion de la quantité
            $form = $this->createForm(QuantityType::class);
            $form->handleRequest($request);
            
            // Si le formulaire est soumis et valide, ajoute le produit au panier
            if ($form->isSubmitted() && $form->isValid()) {
                $quantity = $form->get('quantity')->getData();
                $this->cartService->addToCart($product->getId(), $quantity);
            }

            // Rendu du template Twig pour afficher le produit et le formulaire de quantité
            return $this->render('home/show_product_by_slug.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
            ]);
        }
        
        #Route("/erreur", name="app_error")
        public function errorPage() {
            
            
            
            
            return $this->render('page/not-found.html.twig',[
                'controller_name' => 'PageController'
            ]);
            
        }
        
        public function productList($slider_id): Response
        {
            
            return $this->render('product/list.html.twig', [
            ]);
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
