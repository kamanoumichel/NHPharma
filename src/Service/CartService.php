<?php

namespace App\Service;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService{
    
    private RequestStack $requeststack;
    private EntityManagerInterface $em;

    public function __construct(RequestStack $requeststack,EntityManagerInterface $em)
    {
        $this->requeststack = $requeststack;
        $this->em = $em;
    }
    public function addToCart(int $id): void {
        $card = $this->requeststack->getSession()->get('cart',[]);
        
        if(!empty($card[$id])){
            $card[$id]++;
        }
        else{
            $card[$id]=1;
        }

        $this->getSession()->set('cart',$card);
    }
    public function RemoveToCart(int $id){
        $card = $this->requeststack->getSession()->get('cart',[]);
        unset($card[$id]);
        return $this->getSession()->set('cart',$card);
    }

    public function decrease(int $id)
    {
        $card = $this->requeststack->getSession()->get('cart',[]);
        if ($card[$id] > 1) {
            $card[$id]--;
        }
        else{
            unset($card[$id]);
        }

        $this->getSession()->set('cart',$card);
    }

    public function removeCartAll(){
        return $this->getSession()->remove('cart');
    }

    public function getTotal() : array
    {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        if ($cart) {
            foreach ($cart as $id => $quantity){
                $product = $this->em->getRepository(Produits::class)->findOneBy(['id'=>$id]);
                if(!$product){
                    //Supprimer le produit puis continuer en sortant de la boucle
                    $this->RemoveToCart($id);
                    continue;

                }
                $cartData[] =[
                    'product'=> $product,
                    'quantity'=> $quantity
                ];
            }
        }
        return $cartData;
    }

    private function getSession():SessionInterface{
        return $this->requeststack->getSession();
    }
}