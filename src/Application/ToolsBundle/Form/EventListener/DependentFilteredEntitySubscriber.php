<?php
namespace App\Application\ToolsBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM\EntityRepository;

use Doctrine\Common\EventSubscriber;

use App\Application\ToolsBundle\Form\Type\DependentFilteredEntityType;


class DependentFilteredEntitySubscriber implements EventSubscriberInterface
{
    protected $form_options;
    protected $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->form_options = null;
        $this->fields = array();
    }
    
    public function addField($name, $options) {
        $this->fields[$name] = $options;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = $this->propertyAccessor;
        foreach($this->fields as $name => $options) {
            $object = $data;
            $parent = ($object) ? $accessor->getValue($object, $options['parent_entity_field']): null;
            $parent_id = ($parent)? $parent->getId() : null;
            $this->addDependantForm($form, $parent_id, $options);
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        foreach($this->fields as $name => $options) {
            $parent_field = $options['parent_entity_field'];
            $parent_id = array_key_exists($parent_field, $data) ? $data[$parent_field] : null;

            $this->addDependantForm($form, $parent_id, $options);
        }
    }

    private function addDependantForm($form, $parent_id, $options)
    {
        
        $parent_property = $options['parent_entity_field'];//evento
        
        $field_name = $options['entity_field'];

        $query_builder = function (EntityRepository $repository) use ($parent_id, $parent_property) {
                $qb = $repository->createQueryBuilder('d')
                    ->where('d.' . $parent_property . ' = :parent_id')
                    ->setParameter('parent_id', $parent_id)
                ;
                return $qb;
            };
        $options['query_builder'] = $query_builder;

        $form->add($field_name, DependentFilteredEntityType::class, $options);
    }
}
