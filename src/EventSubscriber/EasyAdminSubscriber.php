<?php


namespace App\EventSubscriber;


use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
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
            BeforeEntityPersistedEvent::class => ['setIllustration']
        ];

    }
    public  function setIllustration(BeforeEntityPersistedEvent $event)
    {
        //$entity = $event->getEntityInstance();
        $entity = $_FILES['Product']['tmp_name']['setIllustration'];
        $tmp_name = $entity->getIllustration();
        $fileName = uniqid();
        $extention = pathinfo($_FILES['Product']['name']['illustration'], PATHINFO_EXTENSION);// png, jpg ...etc
        //dd($extention);

        $project_dir =  $this->appKernel->getProjectDir();
        move_uploaded_file($tmp_name, $project_dir.'/public/uploads/'.$fileName.'.'.$extention);
        $entity->setIllustration($fileName.'.'.$extention);
    }
}