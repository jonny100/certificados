<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;

final class LogoAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            //->add('id')
            ->add('descripcion')
            //->add('url')
            //->add('estado')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            //->add('id')
            ->add('descripcion')
            //->add('url')
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
        // get the current Image instance
        $image = $this->getSubject();

        // use $fileFormOptions so we can add other options to the field
        $fileFormOptions = ['required' => false, 'label' => 'Archivo'];
        if ($image->getFilename() && ($webPath = $image->getWebPath())) {
            // get the request so the full path to the image can be set
            $request = $this->getRequest();
            $fullPath = $request->getBasePath().'/'.$webPath;

            // add a 'help' option containing the preview's img tag
            $fileFormOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview"/>';
            $fileFormOptions['help_html'] = true;
        }
        
        $formMapper
            //->add('id')
            ->add('descripcion', null, array('required' => true))
            //->add('url')
            ->add('file', FileType::class, $fileFormOptions)
            //->add('estado')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            //->add('id')
            ->add('descripcion')
            //->add('url')
            ->add('file', null, array('label' => 'Archivo', 'template' => 'LogoAdmin/show__logo.html.twig'))
            //->add('estado')
            ;
    }
    
    public function prePersist($object)
    {
        $this->manageFileUpload($object);
    }

    public function preUpdate($object) 
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload(object $image): void
    {
        if ($image->getFile()) {
            $image->lifecycleFileUpload();
            $image->refreshUpdated();
        }
    }
}
