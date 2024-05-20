<?php

namespace App\Controller\Admin;

use App\Entity\RecapDetails;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecapDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecapDetails::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            IntegerField::new('quantity'),
            IntegerField::new('prix'),
            IntegerField::new('total_recap'),
            TextField::new('product'),
            AssociationField::new('orderProduct'),
        ];
    }
    
}
