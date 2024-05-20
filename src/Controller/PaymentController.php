<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Produits;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sripe\Stripe;
use Stripe\Stripe as StripeStripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->generator = $generator;
    }
    #[Route('order/create-session-stripe/{reference}',name:'payment_stripe')]
    public function stripeCheckout($reference):RedirectResponse
    {
        $productstripe = [];

        $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if(!$order){
            return $this->redirectToRoute('cart_index');
        }

        foreach($order->getRecapDetails()->getValues() as $product){
            $productData = $this->em->getRepository(Produits::class)->findOneBy(['nom_pro'=> $product->getProduct()]);
            $productstripe[]=[
               'price_data' => [
                    'currency' => 'XAF',
                    'unit_amount' => $productData->getPrix(),
                    'product_data'=>[
                        'name' => $product->getProduct()
                    ]
                    ],
                    'quantity' => $product->getQuantity(),
               ];
        }

        $productstripe[]=[
            'price_data' => [
                 'currency' => 'xaf',
                 'unit_amount' => $order->getTransporterPrice(),
                 'product_data'=>[
                     'name' => $order->getTransporterName()
                 ]
                 ],
                 'quantity' => 1,
            ];
        
        //dd($productstripe);
        StripeStripe::setApiKey('sk_test_51OFqMLKDKKUUeiMBOvmoQVI0Su6VYFZITZoiBkthqcsx60vbnREo7hRMX2EnOH66yDviSYaLYTlUMHlUSrF1uX5U006fYoPplH');
        
        
        $checkout_session = \Stripe\Checkout\Session::create([
        //'customer_email' => $this->getUser()->getEmail(),
        'payment_method_types'=>['card'],
        'line_items' => [[
          $productstripe
        ]],
        'mode' => 'payment',
        'success_url' => $this->generator->generate('payment_success',[
            'reference'=> $order->getReference()
        ], UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generator->generate('payment_error',[
            'reference'=> $order->getReference()
        ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('order/success/{reference}',name:'payment_success')]
    public function StripeSuccess($reference,CartService $service):Response
    {
        return $this->render('order/success.html.twig');
    }
    #[Route('order/error/{reference}',name:'payment_error')]
    public function StripeError($reference,CartService $service):Response
    {
        return $this->render('order/Error.html.twig');
    }
}