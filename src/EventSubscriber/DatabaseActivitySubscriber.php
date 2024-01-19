<?php

namespace App\EventSubscriber;

use App\Entity\Product;

use App\Entity\Category;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;


class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    /** KernelInterface $appKernel */
    private $appKernel;
    
    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
       
    }
    

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
        ];
    }
    public function postRemove(PostRemoveEventArgs $args): void 
    {
        $this->logActivity('remove', $args->getObject());
    }

    public function logActivity(string $action, mixed $entity): void 
    {
        
        if(($entity instanceof Product) && $action === "remove"){
            // dd($entity);
            // remove image
            $imageUrls = $entity->getImageUrls();
            
            foreach ($imageUrls as $imageUrl) {
                $filelink = $this->appKernel->getProjectDir(). "/public/assets/images/products/".$imageUrl;                
                $result = unlink($filelink);
                // dd($filelink);
                try {
                    //code...
                    $result = unlink($filelink);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

        }
        if(($entity instanceof Category) && $action === "remove"){
            // remove image
            $filename = $entity->getImageUrl();

            $filelink = $this->appKernel->getProjectDir(). "/public/assets/images/categories/".$filename;
            $result = unlink($filelink);
            // dd($result);


            
        }
    
        
    }
}
