<?php
namespace App\Application\ToolsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class GeoLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('geo_src', 'hidden')
            ->add('geo_lat', 'hidden')
            ->add('geo_lng', 'hidden')
            ->add('geo_umod', 'hidden')
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
            'marker_title' => '',
            'default_lat' => '',
            'default_lng' => '',
            'readonly'=> false
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['marker_title'] = $options['marker_title'];
        $view->vars['default_lat'] = $options['default_lat'];
        $view->vars['default_lng'] = $options['default_lng'];
        $view->vars['readonly'] = $options['readonly'];
    }

    public function getName()
    {
        return 'geo_location';
    }
}
