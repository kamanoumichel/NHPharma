<?php

namespace App\Controller;

use App\Entity\Categorie;
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

#[Route('/categories', name: 'categories_')]
class CategorieController extends AbstractController
{

    #[Route('/{slug}', name: 'list')]
    public function list(Categorie $category,CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request,PaginatorInterface $paginator): Response
    {
        //On va chercher la liste des produits de la catégorie
        // $products=$category->getProducts();
        
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

         return $this->render('categorie/list.html.twig',['categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),'pro'=> $products->findBy([],['id'=>'desc']),'category'=>$category,'form' => $form->createView(),]);
    }
    #[Route('/', name: 'index')]
    public function categorie(CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request): Response
    {
        //On va chercher la liste des produits de la catégorie
        // $products=$category->getProducts();
       
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
        
         return $this->render('categorie/categorie.html.twig',['categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),'form' => $form->createView(),]);
    }
}
