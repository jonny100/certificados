<?php

declare(strict_types=1);

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class AutorizacionCertificadoAdminController extends CRUDController
{
    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionAutorizar(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $selectedModels = $selectedModelQuery->execute();

        // do the work here
        try {
            foreach ($selectedModels as $inscriptoCertificado) {
                
                $repository = $this->getDoctrine()->getRepository('App:EstadoInscriptoCertificado');
                $estadoInscriptoCertificado = $repository->find(2);
                    
                $em = $this->getDoctrine()->getEntityManager();
                $inscriptoCertificado->setEstado($estadoInscriptoCertificado);
                $em->persist($inscriptoCertificado);
                $em->flush();
                
            }
            
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'Ocurrio un error al actualizar');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'Se actualizó correctamente');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }
    
    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionPendiente(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $selectedModels = $selectedModelQuery->execute();

        // do the work here
        try {
            foreach ($selectedModels as $inscriptoCertificado) {
                
                $repository = $this->getDoctrine()->getRepository('App:EstadoInscriptoCertificado');
                $estadoInscriptoCertificado = $repository->find(1);
                    
                $em = $this->getDoctrine()->getEntityManager();
                $inscriptoCertificado->setEstado($estadoInscriptoCertificado);
                $em->persist($inscriptoCertificado);
                $em->flush();
                
            }
            
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'Ocurrio un error al actualizar');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'Se actualizó correctamente');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }
    
    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionNoAutorizar(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $selectedModels = $selectedModelQuery->execute();

        // do the work here
        try {
            foreach ($selectedModels as $inscriptoCertificado) {
                
                $repository = $this->getDoctrine()->getRepository('App:EstadoInscriptoCertificado');
                $estadoInscriptoCertificado = $repository->find(3);
                    
                $em = $this->getDoctrine()->getEntityManager();
                $inscriptoCertificado->setEstado($estadoInscriptoCertificado);
                $em->persist($inscriptoCertificado);
                $em->flush();
                
            }
            
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'Ocurrio un error al actualizar');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'Se actualizó correctamente');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }

}
