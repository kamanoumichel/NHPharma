<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorieRepository, ArticleRepository $article,PaginatorInterface $paginator,ProduitsRepository $products,Request $request): Response
    {
        $pagination=$paginator->paginate(
            $article->PaginationQuery(),
            $request->query->get('page',1),
            3
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

        
        return $this->render('article/article.html.twig',['categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),'pagination'=>$pagination,'form' => $form->createView(),]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function list(Article $art,CategorieRepository $categorieRepository,ProduitsRepository $products,Request $request): Response
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


         return $this->render('article/detail.html.twig',['categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),'arts'=>$art,'form' => $form->createView(),]);
    }
}
