<?php


namespace App\EventSubscriber;


use App\Entity\Header;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private $appKernel;

    /**
     * EasyAdminSubscriber constructor.
     * @param $appKernel
     */
    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }


    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration'],
        ];

    }

    public function uploadIllustration($event, $entityName)
    {
        $entity = $event->getEntityInstance();
        //$entity = $_FILES['Product']['tmp_name']['illustration'];
        $tmp_name = $_FILES[$entityName]['tmp_name']['illustration'];
        $fileName = uniqid();
        $extention = pathinfo($_FILES[$entityName]['name']['illustration'], PATHINFO_EXTENSION);// png, jpg ...etc
        //dd($extention);

        $project_dir = $this->appKernel->getProjectDir();
        move_uploaded_file($tmp_name, $project_dir . '/public/uploads/' . $fileName . '.' . $extention);
        $entity->setIllustration($fileName . '.' . $extention);

    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product) && !($event->getEntityInstance() instanceof Header)) {
            return;
        }
        $reflextion = new \ReflectionClass($event->getEntityInstance());
        $entityName = $reflextion->getShortName();
        //dd($entityName);

        if ($_FILES[$entityName]['tmp_name']['illustration'] != "") {
            $this->uploadIllustration($event , $entityName);
        }

    }

    public function setIllustration(BeforeEntityPersistedEvent $event)
    {

        if (!($event->getEntityInstance() instanceof Product) && !($event->getEntityInstance() instanceof Header)) {
            return;
        }
        $reflextion = new \ReflectionClass($event->getEntityInstance());
        $entityName = $reflextion->getShortName();
        //dd($entityName);

        $this->uploadIllustration($event , $entityName);
    }


}