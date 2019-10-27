<?php

namespace App\Application\ToolsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ReversedTransformer;

use App\Application\ToolsBundle\Form\DataTransformer\StartEndDateToArrayTransformer;


class StartEndDateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => false,
            'years' => range(date('Y') - 15, date('Y')),
            'months' => range(1, 12)
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $this->configureOptions($resolver);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ychoices = array_combine($options['years'], $options['years']);
        $builder->add('year', 'choice', array(
                'choices' => $ychoices,
                'choice_translation_domain'=> $options['translation_domain'],
                'placeholder' => ' ',
            ))
            ->add('month', 'choice', array(
                'choices' => $this->listMonths($options['months']),
                'placeholder' => ' ',
            ));
            
        $builder->addModelTransformer(new StartEndDateToArrayTransformer());
    }
    
    private function listMonths(array $months)
    {
        $result = array();
        $auxyear = date('Y');

        foreach ($months as $month) {
            $strm = str_pad($month, 2, '0', STR_PAD_LEFT);
            $result[$strm] =date('F', mktime(0,0,0,$month, 1, $auxyear));
        }

        return $result;
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'apptools_startend_date';
    }
}
