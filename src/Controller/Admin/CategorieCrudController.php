<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
   {
        return Categorie::class;
    }
   
    public function configureFields(string $pageName): iterable
    {
        //Categorie=>$cate->getNomCat();
        return [
            
            TextField::new('nom_cat'),
            TextareaField::new('description'),
            SlugField::new('slug')->setTargetFieldName('nom_cat'),
            IntegerField::new('categoryorders'),
            AssociationField::new('parent'),
            
        ];
    }


}
