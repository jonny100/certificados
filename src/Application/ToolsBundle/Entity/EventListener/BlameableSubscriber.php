<?php

namespace App\Application\ToolsBundle\Entity\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;


class BlameableSubscriber implements EventSubscriber
{
    protected $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }
    
    public function getUser() {
        $token = $this->container->get('security.token_storage');
        
        $username = 'system';
        if($token) {
            $user = $token->getUser();
            if($user) {
                $username = $user->getUsername();
			}
        }
        
        return $username;
    }
    
    public function updateBlame($args) {
        $entity = $args->getEntity();
        
        if($this->isEntitySupported($entity)) {
            
            $em =$args->getEntityManager();
            $uow = $em->getUnitOfWork();
            
            $user = $this->getUser();
            
            $creator =$entity->getCreatedBy();
            if(empty($creator)) {
                $entity->setCreatedBy($user);
                $uow->propertyChanged($entity, 'created_by', null, $user);
                $uow->scheduleExtraUpdate($entity,
                    array('created_by' => array(null, $user))
                );
            }
            
            $entity->setUpdatedBy($user);
            $uow->propertyChanged($entity, 'updated_by', null, $user);
            $uow->scheduleExtraUpdate($entity,
                array('updated_by' => array(null, $user))
            );
        }
    }
    
    public function isEntitySupported($entity) {
        $supported = false;
        if( property_exists($entity, 'created_by')
            && property_exists($entity, 'updated_by')) {
            $supported = true;
        }
        return $supported;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateBlame($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateBlame($args);
    }
    
    
}
