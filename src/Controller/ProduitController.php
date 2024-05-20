<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\SearchType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pharmacie', name: 'app_pharmacie_')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request,PaginatorInterface $paginator): Response
    {
        $pagination=$paginator->paginate(
            $products->PaginationQuery(),
            $request->query->get('page',1),
            6
        );

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

        return $this->render('produit/pharmacie.html.twig',[
        'categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),
        'produits'=>$pagination,  
        'form' => $form->createView(),  
    ]
        
    );
    }
    #[Route('/{slug}', name: 'detailproduit')]
    public function detaillist(CategorieRepository $categorieRepository,Produits $productts,ProduitsRepository $products,Request $request): Response
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

        return $this->render('produit/detail.html.twig',[
        'categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),
        'produits'=>$productts,    
        'form' => $form->createView(),
    ]);
    }
}
