<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Route\RouteCollection;

final class InscriptoCertificadoAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection) {               
        $collection->add('certificado', $this->getRouterIdParameter() . '/certificado');
    }
    
    public function  configure()
    {
        $this->parentAssociationMapping = 'inscripto';
    } 

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('certificadoEvento')
            ->add('fechaObt')
            //->add('estado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('certificadoEvento')
            ->add('fechaObt', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'certificadopdf' => ['template' => 'InscriptoCertificadoAdmin/list__action_certificado.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('certificadoEvento')
            ->add('fechaObt', DatePickerType::class, array('format' => 'dd/M/yyyy'))
            //->add('estado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('certificadoEvento')
            ->add('fechaObt', null, array('format' => 'd/m/Y'))
            //->add('estado')
            ;
    }
}
