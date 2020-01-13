<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class InscriptoEventoRequisitoAdmin extends AbstractAdmin
{
    public function  configure()
    {
        $this->parentAssociationMapping = 'inscripto';
    } 

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('certificadoEventoRequisito')
            ->add('excluir')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('certificadoEventoRequisito')
            ->add('excluir')
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
        $inscriptoId = $this->getRequest()->get('id');
        $inscripto = $this->getParent()->getObject($inscriptoId);
        $evento = $inscripto->getEvento();
        
        $formMapper
            //->add('id')
            ->add('certificadoEventoRequisito',  null, array('label' => 'Requisito',
                    'query_builder' => function ($repository)use($evento) {
                        return $repository->createQueryBuilder('cer')
                                ->join('cer.certificadoEvento', 'ce')
                                ->where('ce.evento = :evento')
                                ->groupBy('cer.requisito')
                                ->setParameter('evento', $evento); //SOLAMENTE MUESTRO LOS REQUISITOS DEL EVENTO
                    },
                    'class' => 'App\Entity\CertificadoEventoRequisito',
                    'required' => true
                        ))
            ->add('excluir')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('certificadoEventoRequisito')
            ->add('excluir')
            ;
    }
}
