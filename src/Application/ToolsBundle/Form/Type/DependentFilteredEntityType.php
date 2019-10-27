<?php
namespace App\Application\ToolsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DependentFilteredEntityType extends EntityType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'compound'     => false,
            'placeholder'  => '',
            'choice_label' => null,
            'no_result_msg' => '',
            'form_field' => null, # form field name
            'parent_form_field' => null, # parent form field name
        ));
        
        # class option is required by base class
        $resolver->setRequired(array(
                'entity_alias', # Configuration Id, setup in app/config/application_tools
                'entity_field', # Attribute of the class
                'parent_entity_field', # Attribute of the parent class
                ));
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'apptools_dependent_filtered_entity';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $value = $options['parent_form_field'];
        $value = (null === $value)? $options['parent_entity_field']: $value;
        $builder->setAttribute("parent_form_field", $value);
        
        $value = $options['form_field'];
        $value = (null === $value)? $options['entity_field']: $value;
        $builder->setAttribute("form_field", $value);
        
        $builder->setAttribute("entity_alias", $options['entity_alias']);
        $builder->setAttribute("no_result_msg", $options['no_result_msg']);
        $builder->setAttribute("placeholder", $options['placeholder']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['form_field'] = $form->getConfig()->getAttribute('form_field');
        $view->vars['parent_form_field'] = $form->getConfig()->getAttribute('parent_form_field');
        $view->vars['entity_alias'] = $form->getConfig()->getAttribute('entity_alias');
        $view->vars['no_result_msg'] = $form->getConfig()->getAttribute('no_result_msg');
        $view->vars['placeholder'] = $form->getConfig()->getAttribute('placeholder');
    }
}
