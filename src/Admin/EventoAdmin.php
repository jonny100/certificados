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
use Sonata\AdminBundle\Route\RouteCollection;

final class EventoAdmin extends AbstractAdmin
{
    protected $datagridValues = array(
        '_page' => 1, // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'id',          // name of the ordered field
    );
    
    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('imprimirMasivo'); 
        $collection->add('generarCertificadosMasivo'); 
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni')
            ->add('fechaFin')
            ->add('cupo')
            ->add('correspondiente')
            ->add('resolucion')
            ->add('horas')
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
//            ->add('correspondiente')
            ->add('resolucion')
            ->add('horas')
            ->add('certificados')
            //->add('eventoSgiId')
            //->add('estado')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'inscriptoslist' => ['template' => 'EventoAdmin/list_action_inscriptos.html.twig'],
                    'certificadoslist' => ['template' => 'EventoAdmin/list_action_certificados.html.twig'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('id')
            ->add('descripcion')
            ->add('tipoEvento')
            ->add('fechaIni', DatePickerType::class, array('format' => 'dd/M/yyyy', 'required' => false))
            ->add('fechaFin', DatePickerType::class, array('format' => 'dd/M/yyyy', 'required' => false))
            ->add('cupo')
            ->add('correspondiente')
            ->add('resolucion')
            ->add('horas')
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
            ->add('correspondiente')
            ->add('resolucion')
            ->add('horas')
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
