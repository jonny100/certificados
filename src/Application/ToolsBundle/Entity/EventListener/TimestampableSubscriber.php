<?php

namespace App\Application\ToolsBundle\Entity\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;


class TimestampableSubscriber implements EventSubscriber
{
    
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }
    
    /**
    * Updates createdAt and updatedAt timestamps.
    */
    public function updateTimestamps(LifecycleEventArgs $args)
    {
        $em =$args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entity = $args->getEntity();
        
        if($this->isEntitySupported($entity)) {
            $time = new \DateTime('now');
            if (null === $entity->getCreatedAt()) {
                $entity->setCreatedAt($time);
                $uow->propertyChanged($entity, 'created_at', null, $time);
                $uow->scheduleExtraUpdate($entity,
                    array('created_at' => array(null, $time))
                );
            }
            
            $entity->setUpdatedAt($time);
            $uow->propertyChanged($entity, 'updated_at', null, $time);
            $uow->scheduleExtraUpdate($entity,
                array('updated_at' => array(null, $time))
            );
        }
    }
    
    public function isEntitySupported($entity) {
        $supported = false;
        if( property_exists($entity, 'created_at')
            && property_exists($entity, 'updated_at')) {
            $supported = true;
        }
        return $supported;
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateTimestamps($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateTimestamps($args);
    }
    
    
}
