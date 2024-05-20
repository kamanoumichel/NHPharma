<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private EntityManagerInterface $em;

    #[Route('/search', name: 'app_search', methods:['GET'])]
    public function index(ProduitsRepository $produitsRepository,Request $request,EntityManagerInterface $em): Response
    {
        $searchdata = new SearchData();
        $form = $this->createForm(SearchType::class,$searchdata);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $nom = $searchdata->q;
            if ($nom !="") {
                $product = $produitsRepository->findBy(['nom_pro'=> $nom]);  
                
                $search =[
                    'product'=> $product,
                ];
                
            }
           //dd($search);
           
            return $this->render('search/search.html.twig',[
                'form' => $form,
                'search' => $search,
            ]);
        }
        $search=[];
        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'search' => $search,
            
            
        ]);
    }
}
