<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\RecapDetails;
use App\Form\OrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/create', name: 'app_order')]
    public function index(CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'recapCart'=> $cartService->getTotal()
        ]);
    }
    #[Route('/order/verify/domicile', name: 'order_prepare_domicile', methods:['POST'])]
    public function prepareOrderDomicile(CartService $cartService, Request $request): Response
    {
        return $this->render('order/domicile.html.twig');
    }

    #[Route('/order/verify', name: 'order_prepare', methods:['POST'])]
    public function prepareOrder(CartService $cartService, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new \DateTimeImmutable('now');
            $transporter = $form->get('transporter')->getData();
            $delivery = $form->get('addresses')->getData();
            $deliveryForOrder = $delivery->getNom().' '.$delivery->getPrenom();
            $deliveryForOrder .= '<br>'.$delivery->getTelephone();
            if ($delivery->getCompany()) {
                $deliveryForOrder .= ' - '.$delivery->getCompany();
            }
            $deliveryForOrder .= '<br>'.$delivery->getAddresse();
            $deliveryForOrder .= '<br>'.$delivery->getPays().' - '.$delivery->getVille();
            $order = new Order();
            $reference = $datetime->format('dmY').'-'.uniqid();
            $order->setUser($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDelivery($deliveryForOrder);
            $order->setTransporterName($transporter->getTitre());
            $order->setTransporterPrice($transporter->getPrix());
            $order->setIsPaid(0);
            $order->setMethod('stripe');

            $this->em->persist($order);

            foreach ($cartService->getTotal() as $product) 
            {
                $recapDetails = new RecapDetails();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($product['quantity']);
                $recapDetails->setPrix($product['product']->getPrix());
                $recapDetails->setProduct($product['product']->getNompro());
                $recapDetails->setTotalRecap($product['product']->getPrix() * $product['quantity']);

                $this->em->persist($recapDetails);
            }

            $this->em->flush();

            return $this->render('order/recap.html.twig',[
                'method' => $order->getMethod(),
                'recapCart' => $cartService->getTotal(),
                'transporter' => $transporter,
                'delivery' => $deliveryForOrder,
                'reference' => $order->getReference()
            ]);
        }
        
        
        return $this->redirectToRoute('cart_index');
    }
}
