<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\CartService;
use App\Service\SearchData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{

    #[Route('/mon-panier', name: 'index')]
    public function index(CartService $cartservice,CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request): Response
    {
       
        $searchdata = new SearchData();
        $form = $this->createForm(SearchType::class,$searchdata);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $nom = $searchdata->q;
            if ($nom !="") {
                $product = $products->findBy(['nom_pro'=> $nom]);  
                
                $search =[
                    'product'=> $product,
                ];
                
            }
           
            return $this->render('search/search.html.twig',[
                'form' => $form,
                'search' => $search,
            ]);
        }


        return $this->render('cart/index.html.twig',[
           'cart'=>$cartservice->getTotal(),
           'categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),
           'form' => $form->createView(),
        ]);
    }

    #[Route('/add/{id<\d+>}', name: 'add')]
    public function addToRoute(CartService $cartservice, int $id): Response
    {
        $cartservice->addToCart($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/decrease/{id<\d+>}', name: 'decrease')]
    public function decrease(CartService $cartservice, int $id): RedirectResponse
    {
        $cartservice->decrease($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id<\d+>}', name: 'remove')]
    public function removeToRoute(CartService $cartservice, int $id): Response
    {
        $cartservice->RemoveToCart($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/removeAll', name: 'removeAll')]
    public function removeAll(CartService $cartservice): Response
    {
        $cartservice->removeCartAll();
        return $this->redirectToRoute('app_pharmacie_index');
    }
}
