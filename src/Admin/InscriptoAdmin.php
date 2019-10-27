<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Form\Type\ModelListType;

final class InscriptoAdmin extends AbstractAdmin
{
    public function  configure()
    {
        $this->parentAssociationMapping = 'evento';
    } 

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('persona')
            ->add('legajo')
            ->add('fechaInsc')
            //->add('estado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('persona')
            ->add('legajo')
            ->add('fechaInsc', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'requisitoslist' => ['template' => 'InscriptoAdmin/list_action_requisitos.html.twig'],
                    'certificadoslist' => ['template' => 'InscriptoAdmin/list_action_certificados.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('persona', ModelListType::class)
            ->add('legajo')
            ->add('fechaInsc', DatePickerType::class, array('format' => 'dd/M/yyyy'))
            //->add('estado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('persona')
            ->add('legajo')
            ->add('fechaInsc', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ;
    }
}
