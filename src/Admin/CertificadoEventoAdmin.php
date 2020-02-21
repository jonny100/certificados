<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\Type\ModelListType;

final class CertificadoEventoAdmin extends AbstractAdmin
{
    public function  configure()
    {
        $this->parentAssociationMapping = 'evento';
    } 
    
    protected function configureRoutes(RouteCollection $collection) {               
        $collection->add('certificadoVistaPrevia', $this->getRouterIdParameter() . '/certificadovistaprevia');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('certificado')
            ->add('template')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('certificado')
            ->add('template')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'requisitoslist' => ['template' => 'CertificadoEventoAdmin/list_action_requisitos.html.twig'],
                    'firmaslist' => ['template' => 'CertificadoEventoAdmin/list_action_firmas.html.twig'],
                    'logoslist' => ['template' => 'CertificadoEventoAdmin/list_action_logos.html.twig'],
                    'vistaprevialist' => ['template' => 'CertificadoEventoAdmin/list_action_certificadoVistaPrevia.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('certificado', null, array('required' => true))
            ->add('template', ModelListType::class, array('required' => true))
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('certificado')
            ->add('template')
            ;
    }
}
