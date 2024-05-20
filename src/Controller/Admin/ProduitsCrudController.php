<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('nom_pro'),
            TextareaField::new('detail'),
            IntegerField::new('stock'),
            IntegerField::new('prix'),
            DateField::new('date_per'),
            SlugField::new('slug')->setTargetFieldName('nom_pro'),
            AssociationField::new('sous_categorie'),
            TextField::new('attachmentFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('attachment')->setBasePath('/uploads/attachments')->onlyOnIndex(),

            //TextEditorField::new('description'),
        ];
    }
}
