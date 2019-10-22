<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;

final class EventoAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni')
            ->add('fechaFin')
            ->add('cupo')
            //->add('eventoSgiId')
            //->add('estado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni', null, array('format' => 'd/m/Y'))
            ->add('fechaFin', null, array('format' => 'd/m/Y'))
            ->add('cupo')
            //->add('eventoSgiId')
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
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni', DatePickerType::class, array('format' => 'dd/M/yyyy'))
            ->add('fechaFin', DatePickerType::class, array('format' => 'dd/M/yyyy'))
            ->add('cupo')
            //->add('eventoSgiId')
            //->add('estado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni', null, array('format' => 'd/m/Y'))
            ->add('fechaFin', null, array('format' => 'd/m/Y'))
            ->add('cupo')
            //->add('eventoSgiId')
            //->add('estado')
            ;
    }
}
