<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class AutorizacionCertificadoAdmin extends AbstractAdmin
{
    
    protected $datagridValues = array(
        '_page' => 1, // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'id',          // name of the ordered field
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('certificadoEvento.evento', null, array('label' => 'Evento'))
            ->add('certificadoEvento.certificado', null, array('label' => 'Certificado'))
            ->add('fechaObt', null, array('format' => 'd/m/Y'))
            ->add('estado')
            //->add('codigoVerificacion')
            //->add('textoCertificado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('certificadoEvento.evento', null, array('label' => 'Evento'))
            ->add('certificadoEvento.certificado', null, array('label' => 'Certificado'))
            ->add('inscripto')
            ->add('fechaObt', null, array('format' => 'd/m/Y'))
            ->add('estado', 'choice', array('editable' => true, 
                                            'class' => 'App\Entity\EstadoInscriptoCertificado', 
                                            'choices' => array(
                                            1 => 'PENDIENTE DE AUTORIZACIÓN',
                                            2 => 'AUTORIZADO',
                                            3 => 'NO AUTORIZADO',)))
            //->add('codigoVerificacion')
            //->add('textoCertificado')
//            ->add('_action', null, [
//                'actions' => [
//                    'show' => [],
//                    'edit' => [],
//                    'delete' => [],
//                ],
//            ])
            ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('fechaObt')
            ->add('estado')
            //->add('codigoVerificacion')
            //->add('textoCertificado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('fechaObt')
            ->add('estado')
            //->add('codigoVerificacion')
            //->add('textoCertificado')
            ;
    }
    
    protected function configureBatchActions($actions)
    {
        $actions['Pendiente'] = [
                   'ask_confirmation' => true,
                   'label' => 'PENDIENTE DE AUTORIZACIÓN',
                   'InscriptoCertificadoId' => $this->getSubject()
               ];
        $actions['Autorizar'] = [
                   'ask_confirmation' => true,
                   'label' => 'AUTORIZAR',
                   'inscriptoCertificadoId' => $this->getSubject()
               ];
        $actions['NoAutorizar'] = [
                   'ask_confirmation' => true,
                   'label' => 'NO AUTORIZAR',
                   'InscriptoCertificadoId' => $this->getSubject()
               ];
        
        return $actions;
    }
    
}
