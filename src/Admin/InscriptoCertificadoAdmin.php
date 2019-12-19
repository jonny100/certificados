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
        $collection->add('enviarcertificadomail', $this->getRouterIdParameter() . '/enviarcertificadomail');
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
                    'certificadomail' => ['template' => 'InscriptoCertificadoAdmin/list__action_certificado_mail.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $inscriptoId = $this->getRequest()->get('id');
        $inscripto = $this->getParent()->getObject($inscriptoId);
        $evento = $inscripto->getEvento();
        
        $formMapper
            //->add('id')
            ->add('certificadoEvento',  null, array('label' => 'Certificado',
                    'query_builder' => function ($repository)use($evento) {
                        return $repository->createQueryBuilder('c')
                                ->where('c.evento = :evento')->setParameter('evento', $evento); //SOLAMENTE MUESTRO LOS CERTIFICADOS DEL EVENTO
                    },
                    'class' => 'App\Entity\CertificadoEvento',
                    'required' => true
                        ))
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
