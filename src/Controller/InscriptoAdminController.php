<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Entity\CertificadoEvento;
use App\Entity\EstadoInscriptoCertificado;
use App\Entity\Evento;
use App\Entity\Inscripto;
use App\Entity\InscriptoCertificado;
use App\Entity\Persona;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
                    
                    $em = $this->getDoctrine()->getManager();
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

    public function importarInscriptosCSVAction(Request $request)
    {
        $eventoId = $request->get('id');
        $repository = $this->getDoctrine()->getRepository('App:Evento');
        /** @var Evento $evento */
        $evento = $repository->find($eventoId);

        $form = $this->createFormBuilder()//()
        ->add('file', FileType::class, ['label' => 'CSV de inscriptos', 'required' => true])
        ->add('procesar', SubmitType::class, array('label' => 'Importar', "attr" => array('class' => "btn btn-primary")))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file->getClientOriginalExtension() !== 'csv') {
                $this->addFlash('danger', 'El archivo debe ser un CSV.');
                return $this->redirectToRoute('admin_app_inscripto_importarInscriptosCSV', ['id' => $eventoId]);
            }

            $handle = fopen($file->getPathname(), 'r');
            if (!$handle) {
                $this->addFlash('danger', 'No se pudo abrir el archivo CSV.');
                return $this->redirectToRoute('admin_app_inscripto_importarInscriptosCSV', ['id' => $eventoId]);
            }

            $header = fgetcsv($handle, 0, ';');
            $header = array_map(function ($value) {
                // Eliminar BOM (si existe) y luego aplicar trim para quitar espacios en blanco
                $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
                return trim($value);
            }, $header);
            if (!$header || count($header) !== 2 || $header[0] !== 'DNI' || $header[1] !== 'Apellido y nombre') {
                fclose($handle);
                $this->addFlash('danger', 'El formato del CSV no es válido. La primera fila debe contener: DNI;Apellido y nombre.');
                return $this->redirectToRoute('admin_app_inscripto_importarInscriptosCSV', ['id' => $eventoId]);
            }

            $inscriptos = [];
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($row) !== 2) {
                    continue;
                }

                [$dni, $nombreCompleto] = $row;

                // Validar DNI (número positivo y sin letras)
                if (!ctype_digit($dni) || intval($dni) <= 0) {
                    continue;
                }

                $inscriptos[] = [
                    'dni' => $dni,
                    'nombreCompleto' => $nombreCompleto,
                ];

            }

            fclose($handle);

            $certificados = $evento->getCertificados();

            // Filtrar el certificado con la descripción "CERTIFICADO EN BLANCO"
            /** @var CertificadoEvento $certificadoEnBlanco */
            $certificadoEnBlanco = null;
            foreach ($certificados as $certificado) {
                if ($certificado->getCertificado()->getDescripcion() === "CERTIFICADO EN BLANCO") {
                    $certificadoEnBlanco = $certificado;
                    break;
                }
            }

            if(!$certificadoEnBlanco){
                $this->addFlash('danger', 'El evento no tiene un CERTIFICADO EN BLANCO.');
                return $this->redirectToRoute('admin_app_inscripto_importarInscriptosCSV', ['id' => $eventoId]);
            }

            foreach ($inscriptos as $inscripto) {
                $this->generarInscriptoDesdeCSV($inscripto, $evento, $certificadoEnBlanco);
            }

            if (empty($inscriptos)) {
                $this->addFlash('warning', 'No se encontraron registros válidos en el CSV.');
            } else {
                $this->addFlash('success', count($inscriptos) . ' inscriptos procesados correctamente.');
            }

            return $this->redirectToRoute('admin_app_inscripto_importarInscriptosCSV', ['id' => $eventoId]);

        }

        $vars = array(
            'action' => 'importarinscriptoscsv',
            'evento' => $evento,
            'form' => $form->createView(),
        );

        return $this->render('InscriptoAdmin\importarInscriptosCSV.html.twig', $vars);
    }

    private function generarInscriptoDesdeCSV($inscripto, Evento $evento, CertificadoEvento $certificadoEnBlanco)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('App:Persona');
        $persona = $repository->findOneBy(array('dni' => $inscripto['dni']));

        if(!$persona){
            $persona = new Persona();
            $persona->setDni($inscripto['dni']);
            $persona->setApellidoNombre($inscripto['nombreCompleto']);

            $em->persist($persona);
            $em->flush();
        }

        $inscriptoExistente = $em->getRepository(Inscripto::class)->findOneBy([
            'persona' => $persona,
            'evento' => $evento
        ]);

        if ($inscriptoExistente) {
            $this->addFlash('info', $persona . 'Ya se encuentra inscripta en este evento.');
            return;
        }

        $inscripto = new Inscripto();
        $inscripto->setPersona($persona);

        $inscriptoCertificado = new InscriptoCertificado();
        $inscriptoCertificado->setCertificadoEvento($certificadoEnBlanco);
        $estadoAutorizado = $em->getRepository(EstadoInscriptoCertificado::class)->find(2);
        $inscriptoCertificado->setEstado($estadoAutorizado);
        $userCreadorAutorizador = $em->getRepository(User::class)->find(1);
        $inscriptoCertificado->setUserCreador($userCreadorAutorizador);
        $inscriptoCertificado->setUserAutorizador($userCreadorAutorizador);

        $inscripto->addCertificado($inscriptoCertificado);

        $evento->addInscripto($inscripto);

        $em->persist($evento);
        $em->flush();

    }
}
