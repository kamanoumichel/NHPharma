<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\Transporter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        //$user = $options['user'];
        $builder
            ->add('addresses',EntityType::class,[
                'class'=> Address::class,
                'label'=>false,
                'required'=>true,
                'multiple'=>false,
                //'choices'=> $user->getAdresse(),
                'expanded'=>true,
            ])
            ->add('transporter',EntityType::class,[
                'class'=> Transporter::class,
                'label'=> false,
                'required'=> true,
                'multiple'=> false,
                'expanded'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' =>[]
        ]);
    }
}
