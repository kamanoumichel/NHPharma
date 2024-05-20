<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategorieRepository $categorieRepository,ArticleRepository $article,ProduitsRepository $products,Request $request): Response
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

        return $this->render('home/index.html.twig',[
            'categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),
            'article'=>$article->findBy([],['id'=>'desc']),
            'produits'=>$products->findBy([],['id'=>'desc']),
            'form' => $form->createView(),
        ]);
    }
}
