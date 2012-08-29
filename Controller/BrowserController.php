<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     *
     * @Route("/", name="dezull_help_browser_index_notitle")
     * @Route("/!{title}", name="dezull_help_browser_index")
     * @Template()
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

        return compact('topic');
    }

    /**
     * List categories & topics
     *
     * @Route("/topicTree", name="dezull_help_browser_topictree")
     * @Template()
     */
    public function topicTreeAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categories = $em->getRepository('DezullHelpBundle:HelpCategory')->findAll();
        $tree = array();
        $topicRepo = $em->getRepository('DezullHelpBundle:HelpTopic');

        foreach ($categories as $category) {
            $tree[$category->getName()] = $topicRepo->findByCategory($category->getId());
        }

        return compact('tree');
    }
}
