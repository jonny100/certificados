<?php
namespace App\Application\ToolsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CloakEntityType extends EntityType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'cloak'     => null,
            'cloak_selector'=> null,
        ));        
        # class option is required by base class
    }

    public function getName()
    {
        return 'apptools_cloak_entity';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->setAttribute("cloak", $options['cloak']);
        $builder->setAttribute("cloak_selector", $options['cloak_selector']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $cloak_options = $form->getConfig()->getAttribute('cloak');
        $opts = array();
        foreach($cloak_options as $key=>$val) {
            $opts[$key] = array();
            foreach($val as $action=>$fields) {
                $opts[$key][$action] = array();
                foreach($fields as $field) {
                    $opts[$key][$action][] = $field;
                }
            }
        }

        $view->vars['cloak'] = json_encode($opts);
        $view->vars['cloak_selector'] = $form->getConfig()->getAttribute('cloak_selector');
        $view->vars['cloak_debug'] = json_encode($opts);
    }

}

