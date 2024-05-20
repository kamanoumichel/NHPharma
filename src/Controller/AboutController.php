<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    
    #[Route('/about', name: 'app_about')]
    public function index(CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request): Response
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

        return $this->render('about/about.html.twig',['categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),'form' => $form->createView()]);
    }
}
