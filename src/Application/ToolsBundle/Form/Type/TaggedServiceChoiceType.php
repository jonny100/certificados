<?php
namespace App\Application\ToolsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;
#use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TaggedServiceChoiceType extends AbstractType
{
    protected $service_container;

    public function __construct($container) {
        $this->service_container = $container;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $lister = $this->service_container->get('application_tools.tagged_service_lister');
        # $choices = getServiceIds(
        $resolver->setDefaults(array(
            'choices'=> array('Choice1'=>'Choice1', 'Choice2s'=>'Choice2'),
        ));
        
        $resolver->setRequired(array(
            'tag',
            ));
    }

    public function getName()
    {
        return 'tagged_service_choice';
    }
    
    public function getParent()
    {
        return 'choice';
    }
}

