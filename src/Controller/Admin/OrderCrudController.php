<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail')
            ->disable(Action::DELETE, Action::EDIT, Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('reference')->setLabel('Reference'),  
            TextField::new('user.getLastname')->setLabel('Client - Nom'),  
            TextField::new('user.getFirstName')->setLabel('Prénom'),  
            EmailField::new('user.getEmail')->setLabel('Email'),  
            BooleanField::new('paid')->renderAsSwitch(false)->setLabel('Payée'),
            DateTimeField::new('createdAt')->setLabel('Passée le'),
            MoneyField::new('getTotalFormat')->setCurrency('EUR')->setLabel('Montant'),
            Field::new('getListArticles')->setLabel('Liste des articles')->onlyOnDetail(),
            TextField::new('carrierName')->setLabel('Transporteur')->onlyOnDetail(),
            MoneyField::new('getCarrierPriceFormat')->setCurrency('EUR')->setLabel('Frais de livraison')->onlyOnDetail(),
            TextField::new('getDelivery')->setLabel('Adresse de livraison')->onlyOnDetail(),           
                   
        ];
    }
}
