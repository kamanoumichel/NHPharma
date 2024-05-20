<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Form\SearchType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use App\Service\SearchData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $manage,ProduitsRepository $products,CategorieRepository $categorieRepository): Response
    {
        $contact = new Contact();
        //On créée le formulaire
        $contactform = $this->createForm(ContactFormType::class, $contact);
        
        //On traite la requête du formulaire
        $contactform->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if($contactform->isSubmitted() && $contactform->isValid())
        {
            //On Stock
            $manage->persist($contact);
            $manage->flush();

            $this->addFlash('success','Votre message a étè envoyé.');
            //On redirige
            return $this->redirectToRoute('app_contact');
        }


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

        return $this->render('contact/index.html.twig', [
            'contact' => $contactform->createView(),'categories'=>$categorieRepository->findBy([],['categoryorders'=>'asc']),
            'form' => $form->createView(),
        ]);
    }
}
