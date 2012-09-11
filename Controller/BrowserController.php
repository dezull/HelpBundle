<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dezull\Bundle\HelpBundle\Entity\HelpTopic;
use Dezull\Bundle\HelpBundle\Form\HelpTopicType;

/**
 * Help Browser controller.
 */
class BrowserController extends Controller
{
    /**
     * Show topic
     *
     * @param string $title Topic title
     * @return Response A Response instance
     */
    public function indexAction($title = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $topic = null;
        if ($title) {
            $topic = $em->getRepository('DezullHelpBundle:HelpTopic')->findOneByTitle($title);

            if (!$topic) {
                throw $this->createNotFoundException();
            }
        }

        return $this->render('DezullHelpBundle:Browser:index.html.twig', array(
            'topic' => $topic,
        ));
    }

    /**
     * List categories & topics
     *
     * @param int $selectTopicId Topic id
     * @return Response A Response instance
     */
    public function topicTreeAction($selectTopicId = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categories = $em->getRepository('DezullHelpBundle:HelpCategory')->findAllOrderBySequence();
        $topicRepo = $em->getRepository('DezullHelpBundle:HelpTopic');
        $tree = array();

        foreach ($categories as $category) {
            $tree[$category->getName()] = $topicRepo->findByCategoryOrderBySequence($category->getId());
        }

        return $this->render('DezullHelpBundle:Browser:topicTree.html.twig', array(
            'tree' => $tree,
            'selectTopicId' => $selectTopicId,
        ));
    }
}
