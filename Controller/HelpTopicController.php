<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dezull\Bundle\HelpBundle\Entity\HelpTopic;
use Dezull\Bundle\HelpBundle\Form\HelpTopicType;

/**
 * HelpTopic controller.
 *
 * @Route("/topic")
 */
class HelpTopicController extends Controller
{
    /**
     * Lists all HelpTopic entities.
     *
     * @Route("/", name="dezull_help_topic")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('DezullHelpBundle:HelpTopic')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a HelpTopic entity.
     *
     * @Route("/{id}/show", name="dezull_help_topic_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new HelpTopic entity.
     *
     * @Route("/new", name="dezull_help_topic_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new HelpTopic();
        $form   = $this->createForm($this->getForm(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new HelpTopic entity.
     *
     * @Route("/create", name="dezull_help_topic_create")
     * @Method("post")
     * @Template("DezullHelpBundle:HelpTopic:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new HelpTopic();
        $request = $this->getRequest();
        $form    = $this->createForm($this->getForm(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_topic_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing HelpTopic entity.
     *
     * @Route("/{id}/edit", name="dezull_help_topic_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $editForm = $this->createForm($this->getForm(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing HelpTopic entity.
     *
     * @Route("/{id}/update", name="dezull_help_topic_update")
     * @Method("post")
     * @Template("DezullHelpBundle:HelpTopic:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $editForm   = $this->createForm($this->getForm(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_topic_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a HelpTopic entity.
     *
     * @Route("/{id}/delete", name="dezull_help_topic_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HelpTopic entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dezull_help_topic'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    private function getForm()
    {
        // Optional dependency on TrsteelCkeditorBundle

        if (\class_exists('Trsteel\CkeditorBundle\TrsteelCkeditorBundle')) {
            $form = $this->get('dezull_help.topic.type');
        } else {
            $form = new \Dezull\Bundle\HelpBundle\Form\SimpleHelpTopicType();
        }

        return $form;
    }
}
