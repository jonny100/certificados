<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\CoreBundle\Form\Type\CollectionType;

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
            ->add('certificados')
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
            ->add('certificados')
            //->add('eventoSgiId')
            //->add('estado')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'inscriptoslist' => ['template' => 'EventoAdmin/list_action_inscriptos.html.twig'],
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
            ->add('certificados', CollectionType::class, array('label' => "Certificados", 'btn_add' => 'Agregar Certificado'), array(
                    'edit' => 'inline',
                    'allow_delete' => true,
                    'inline' => 'standard'))
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
            ->add('certificados')
            //->add('eventoSgiId')
            //->add('estado')
            ;
    }
    
    public function prePersist($object) {
        $object->setCertificados($object->getCertificados());
    }

    public function preUpdate($object) {
        $object->setCertificados($object->getCertificados());
    }
}
