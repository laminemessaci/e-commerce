<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    /**
     * OrderCrudController constructor.
     * @param $entityManager
     * @param $crudUrlGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }


    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $update_preparation = Action::new('update_preparation', 'En cours de préparation', 'fas fa-box-open')->linkToCrudAction('update_preparation');
        $update_delivery = Action::new('update_delivery', 'En cours de livraison',  'fas fa-truck')->linkToCrudAction('update_delivery');
        return $actions
            ->add('detail', $update_preparation)
            ->add('detail', $update_delivery)
            ->add('index', 'detail');
    }

    public function update_preparation(AdminContext $context)
    {
        //die('ok');
        $order = $context->getEntity()->getInstance();
        //dd($order);
        $order->setState(2);
        $this->entityManager->flush();
        $this->addFlash('notice', '<strong class="alert alert-success">Info! La commande: 
                                               <span style="color: #aa1111">' . $order->getReference() . '
                                               </span> a été mise à jour</strong> </div>');
        // ici on peut ajouter par exemple un envoie de mail
        // automatique au client pour informer du statut de la commande
        //      TODO

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        return $this->redirect($url);
    }

    public function update_delivery(AdminContext $context)
    {
        //die('ok');
        $order = $context->getEntity()->getInstance();
        //dd($order);
        $order->setState(3);
        $this->entityManager->flush();
        $this->addFlash('notice', '<strong class="alert alert-warning">Info! La commande: 
                                                 <span style="color: #d39e00">' . $order->getReference() . '
                                                 </span> a été mise à jour</strong> </div>');
        // ici on peut ajouter par exemple un envoie de mail
        // automatique au client pour informer du statut de la commande
        //      TODO


        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Commande passée le '),
            TextField::new('user.fullName', 'Client '),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur '),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state')->setChoices([
                    'Non payée' => 0,
                    'Payée' => 1,
                    'En cours de préparation' => 2,
                    'En cours de livraison' => 3,
                ]
            ),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
        ];
    }

}
