<?php

declare(strict_types=1);

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class InscriptoAdminController extends CRUDController
{
    /**
     * Batch action.
     *
     * @throws NotFoundHttpException If the HTTP method is not POST
     * @throws \RuntimeException     If the batch action is not defined
     *
     * @return Response|RedirectResponse
     */
    public function batchAction()
    {
        $request = $this->getRequest();
        $restMethod = $this->getRestMethod();

        if ('POST' !== $restMethod) {
            throw $this->createNotFoundException(sprintf('Invalid request type "%s", POST expected', $restMethod));
        }

        // check the csrf token
        $this->validateCsrfToken('sonata.batch');

        $confirmation = $request->get('confirmation', false);

        if ($data = json_decode((string) $request->get('data'), true)) {
            $action = $data['action'];
            $idx = $data['idx'];
            $allElements = $data['all_elements'];
            $request->request->replace(array_merge($request->request->all(), $data));
        } else {
            $request->request->set('idx', $request->get('idx', []));
            $request->request->set('all_elements', $request->get('all_elements', false));

            $action = $request->get('action');
            $idx = $request->get('idx');
            $allElements = $request->get('all_elements');
            $data = $request->request->all();

            unset($data['_sonata_csrf_token']);
        }

        // NEXT_MAJOR: Remove reflection check.
        $reflector = new \ReflectionMethod($this->admin, 'getBatchActions');
        if ($reflector->getDeclaringClass()->getName() === \get_class($this->admin)) {
            @trigger_error('Override Sonata\AdminBundle\Admin\AbstractAdmin::getBatchActions method'
                .' is deprecated since version 3.2.'
                .' Use Sonata\AdminBundle\Admin\AbstractAdmin::configureBatchActions instead.'
                .' The method will be final in 4.0.', E_USER_DEPRECATED
            );
        }
        $batchActions = $this->admin->getBatchActions();
        if (!\array_key_exists($action, $batchActions)) {
            throw new \RuntimeException(sprintf('The `%s` batch action is not defined', $action));
        }

        $camelizedAction = Inflector::classify($action);
        $isRelevantAction = sprintf('batchAction%sIsRelevant', $camelizedAction);

        if (method_exists($this, $isRelevantAction)) {
            $nonRelevantMessage = $this->{$isRelevantAction}($idx, $allElements, $request);
        } else {
            $nonRelevantMessage = 0 !== \count($idx) || $allElements; // at least one item is selected
        }

        if (!$nonRelevantMessage) { // default non relevant message (if false of null)
            $nonRelevantMessage = 'flash_batch_empty';
        }

        $datagrid = $this->admin->getDatagrid();
        $datagrid->buildPager();

        if (true !== $nonRelevantMessage) {
            $this->addFlash(
                'sonata_flash_info',
                $this->trans($nonRelevantMessage, [], 'SonataAdminBundle')
            );

            return $this->redirectToList();
        }

        $askConfirmation = $batchActions[$action]['ask_confirmation'] ??
            true;

        if ($askConfirmation && 'ok' !== $confirmation) {
            $actionLabel = $batchActions[$action]['label'];
            $batchTranslationDomain = $batchActions[$action]['translation_domain'] ??
                $this->admin->getTranslationDomain();

            $formView = $datagrid->getForm()->createView();
            $this->setFormTheme($formView, $this->admin->getFilterTheme());

            // NEXT_MAJOR: Remove these lines and use commented lines below them instead
            $template = !empty($batchActions[$action]['template']) ?
                $batchActions[$action]['template'] :
                $this->admin->getTemplate('batch_confirmation');
            // $template = !empty($batchActions[$action]['template']) ?
            //     $batchActions[$action]['template'] :
            //     $this->templateRegistry->getTemplate('batch_confirmation');

            return $this->renderWithExtraParams($template, [
                'action' => 'list',
                'action_label' => $actionLabel,
                'batch_translation_domain' => $batchTranslationDomain,
                'datagrid' => $datagrid,
                'form' => $formView,
                'data' => $data,
                'csrf_token' => $this->getCsrfToken('sonata.batch'),
            ], null);
        }

        // execute the action, batchActionXxxxx
        $finalAction = sprintf('batchActionAsignarRequisito', $camelizedAction);
        if (!method_exists($this, $finalAction)) {
            throw new \RuntimeException(sprintf('A `%s::%s` method must be callable', static::class, $finalAction));
        }

        $query = $datagrid->getQuery();

        $query->setFirstResult(null);
        $query->setMaxResults(null);

        $this->admin->preBatchAction($action, $query, $idx, $allElements);

        if (\count($idx) > 0) {
            $this->admin->getModelManager()->addIdentifiersToQuery($this->admin->getClass(), $query, $idx);
        } elseif (!$allElements) {
            $this->addFlash(
                'sonata_flash_info',
                $this->trans('flash_batch_no_elements_processed', [], 'SonataAdminBundle')
            );

            return $this->redirectToList();
        }
        $certificadoEventoRequisitoId = $batchActions[$action]['CertificadoEventoRequisitoId'];
        return $this->{$finalAction}($query, $request, $certificadoEventoRequisitoId);
    }
    
    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, array $theme = null): void
    {
        $twig = $this->get('twig');

        // BC for Symfony < 3.2 where this runtime does not exists
        if (!method_exists(AppVariable::class, 'getToken')) {
            $twig->getExtension(FormExtension::class)->renderer->setTheme($formView, $theme);

            return;
        }

        // BC for Symfony < 3.4 where runtime should be TwigRenderer
        if (!method_exists(DebugCommand::class, 'getLoaderPaths')) {
            $twig->getRuntime(TwigRenderer::class)->setTheme($formView, $theme);

            return;
        }

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }
    
    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionAsignarRequisito(ProxyQueryInterface $selectedModelQuery, Request $request = null, $certificadoEventoRequisitoId)
    {
        $selectedModels = $selectedModelQuery->execute();

        // do the work here
        try {
            $repository = $this->getDoctrine()->getRepository('App:CertificadoEventoRequisito');
            $certificadoEventoRequisito = $repository->find($certificadoEventoRequisitoId);
            $repository2 = $this->getDoctrine()->getRepository('App:InscriptoEventoRequisito');
            foreach ($selectedModels as $inscripto) {
                
                $inscriptoEventoRequisito = $repository2->findOneBy(array('inscripto' => $inscripto->getId(), 'certificadoEventoRequisito' => $certificadoEventoRequisitoId));
                if(!isset($inscriptoEventoRequisito)){
                    $inscriptoER = new \App\Entity\InscriptoEventoRequisito;
                    $inscriptoER->setInscripto($inscripto);
                    $inscriptoER->setCertificadoEventoRequisito($certificadoEventoRequisito);
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($inscriptoER);
                    $em->flush();
                }
            }
            
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'Ocurrio un error al otorgar los requisitos');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'Se otorgaron correctamente los requisitos');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }
}
