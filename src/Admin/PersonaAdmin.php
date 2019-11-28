<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class PersonaAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('apellidoNombre')
            ->add('dni')
            ->add('direccion')
            ->add('email')
            ->add('telefono')
            ->add('sexo')
            ->add('fechaNac')
            //->add('estado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('apellidoNombre')
            ->add('dni')
            ->add('direccion')
            ->add('email')
            ->add('telefono')
            ->add('sexo')
            ->add('fechaNac', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('apellidoNombre')
            ->add('dni', null, array('required' => true))
            ->add('direccion')
            ->add('email')
            ->add('telefono')
            ->add('sexo', ChoiceType::class, array(
                        'choices'   => array('Masculino' => 'M', 'Femenino' => 'F'),
                        'required'  => false,
                        'label' => 'Genero'   
                    ))
            ->add('fechaNac', DatePickerType::class, array('format' => 'dd/M/yyyy'))
            //->add('estado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('apellidoNombre')
            ->add('dni')
            ->add('direccion')
            ->add('email')
            ->add('telefono')
            ->add('sexo')
            ->add('fechaNac', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ;
    }
}
