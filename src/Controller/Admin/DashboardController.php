<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Contact;
use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\Produits;
use App\Entity\RecapDetails;
use App\Entity\Transporter;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;



class DashboardController extends AbstractDashboardController
{

   

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(CategorieCrudController::class)->generateUrl());


        // $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        // ...set chart data and options somehow

        

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("<a href=\"/\"><img src=\"assets/images/NHP.png\" height=70px width=70px> NHPharma</a>");

             // by default EasyAdmin displays a black square as its default favicon;
            // use this method to display a custom favicon: the given path is passed
            // "as is" to the Twig asset() function:
            // <link rel="shortcut icon" href="{{ asset('...') }}">
            // ->setFaviconPath('favicon.svg')
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-tags', Categorie::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-basket-shopping', Produits::class);
        yield MenuItem::linkToCrud('Demande de Contact', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Article', 'fa-regular fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fa-solid fa-user-large', User::class);
        yield MenuItem::linkToCrud('Transporteur', 'fa-solid fa-cart-flatbed', Transporter::class);
        yield MenuItem::linkToCrud('Commande', 'fa-solid fa-money-check-dollar', Order::class);
        yield MenuItem::linkToCrud('Détail des Commandes', 'fa-solid fa-circle-info', RecapDetails::class);
        yield MenuItem::linkToCrud('Coupon', 'fa-solid fa-clipboard-check', Coupon::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
