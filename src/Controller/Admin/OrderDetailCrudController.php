<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetail::class;
    }
    public function configureActions(Actions $actions): Actions{
        return $actions 
        ->add(Crud::PAGE_EDIT, Action::INDEX)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->add(Crud::PAGE_EDIT, Action::DETAIL)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('order'),
            AssociationField::new('product')->formatValue(
                function ($value, $entity) {
                    return $value->getName(); 
                }
            ),
            IntegerField::new('quantity'),
            MoneyField::new('totalCost')->setCurrency("EUR")->setStoredAsCents(false),
        ];
    }
    
}
