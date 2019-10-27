<?php

namespace App\Application\ToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

use Symfony\Component\HttpFoundation\RequestStack;

class DependentFilteredEntityController extends Controller
{
	protected $requestStack;
	
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }	


    public function getOptionsAction()
    {

        $em = $this->get('doctrine')->getManager();
        $request = $this->requestStack->getCurrentRequest();
		
		
        $translator = $this->get('translator');

        $entity_alias = $request->get('entity_alias');
        $parent_id    = $request->get('parent_id');
        $placeholder  = $request->get('placeholder');

        $entities = $this->get('service_container')->getParameter('application_tools.dependent_filtered_entities');
        $entity_inf = $entities[$entity_alias];
        $entity_inf = array_merge($entity_inf['form_options'],
                                  $entity_inf['search_options']);

        if ($entity_inf['role'] !== 'IS_AUTHENTICATED_ANONYMOUSLY') {
            $checker = $this->get('security.authorization_checker');
            if (false === $checker->isGranted($entity_inf['role'])) {
                throw new AccessDeniedException();
            }
        }

        $qb = $this->getDoctrine()
                ->getRepository($entity_inf['class'])
                ->createQueryBuilder('e')
                ->where('e.' . $entity_inf['parent_entity_field'] . ' = :parent_id')
                ->orderBy('e.' . $entity_inf['order_field'], $entity_inf['order_direction'])
                ->setParameter('parent_id', $parent_id);


        if (null !== $entity_inf['callback']) {
            $repository = $qb->getEntityManager()->getRepository($entity_inf['class']);

            if (!method_exists($repository, $entity_inf['callback'])) {
                throw new \InvalidArgumentException(sprintf('Callback function "%s" in Repository "%s" does not exist.', $entity_inf['callback'], get_class($repository)));
            }

            $repository->$entity_inf['callback']($qb);
        }

        $results = $qb->getQuery()->getResult();

        if (empty($results)) {
            return new Response('<option value="">' . $translator->trans($entity_inf['no_result_msg']) . '</option>');
        }

        $html = '';
        if ($placeholder !== false)
            $html .= '<option value="">' . $translator->trans($placeholder) . '</option>';

        $getter =  PropertyAccess::createPropertyAccessor();
        $choice_label = $entity_inf['choice_label'];
        
        foreach($results as $result)
        {
            if ($choice_label)
                $res = $getter->getValue($result, $choice_label);
            else $res = (string)$result;

            $html = $html . sprintf("<option value=\"%d\">%s</option>",$result->getId(), $res);
        }

        return new Response($html);

    }


    /*
        TODO: Is this function used ??? Verify.
    */
    public function getJSONAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->get('request');

        $entity_alias = $request->get('entity_alias');
        $parent_id    = $request->get('parent_id');
        $placeholder  = $request->get('placeholder');

        $entities = $this->get('service_container')->getParameter('toolsbundle.dependent_filtered_entities');
        $entity_inf = $entities[$entity_alias];

        if ($entity_inf['role'] !== 'IS_AUTHENTICATED_ANONYMOUSLY'){
            if (false === $this->get('security.context')->isGranted( $entity_inf['role'] )) {
                throw new AccessDeniedException();
            }
        }

        $term = $request->get('term');
        $maxRows = $request->get('maxRows', 20);

        $like = '%' . $term . '%';

        $property = $entity_inf['entity_field'];
        if (!$entity_inf['property_complicated']) {
            $property = 'e.' . $property;
        }

        $qb = $em->createQueryBuilder()
            ->select('e')
            ->from($entity_inf['class'], 'e')
            ->where('e.' . $entity_inf['parent_entity_field'] . ' = :parent_id')
            ->setParameter('parent_id', $parent_id)
            ->orderBy('e.' . $entity_inf['order_field'], $entity_inf['order_direction'])
            ->setParameter('like', $like )
            ->setMaxResults($maxRows);

        if ($entity_inf['case_insensitive']) {
            $qb->andWhere('LOWER(' . $property . ') LIKE LOWER(:like)');
        } else {
            $qb->andWhere($property . ' LIKE :like');
        }

        $results = $qb->getQuery()->getResult();

        $res = array();
        foreach ($results AS $r){
            $res[] = array(
                'id' => $r->getId(),
                'text' => (string)$r
            );
        }

        return new Response(json_encode($res));
    }
}
