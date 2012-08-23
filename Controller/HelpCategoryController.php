<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dezull\Bundle\HelpBundle\Entity\HelpCategory;
use Dezull\Bundle\HelpBundle\Form\HelpCategoryType;

/**
 * HelpCategory controller.
 *
 * @Route("/category")
 */
class HelpCategoryController extends Controller
{
    /**
     * Lists all HelpCategory entities.
     *
     * @Route("/", name="dezull_help_category")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('DezullHelpBundle:HelpCategory')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a HelpCategory entity.
     *
     * @Route("/{id}/show", name="dezull_help_category_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new HelpCategory entity.
     *
     * @Route("/new", name="dezull_help_category_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new HelpCategory();
        $form   = $this->createForm(new HelpCategoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new HelpCategory entity.
     *
     * @Route("/create", name="dezull_help_category_create")
     * @Method("post")
     * @Template("DezullHelpBundle:HelpCategory:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new HelpCategory();
        $request = $this->getRequest();
        $form    = $this->createForm(new HelpCategoryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_category_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing HelpCategory entity.
     *
     * @Route("/{id}/edit", name="dezull_help_category_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpCategory entity.');
        }

        $editForm = $this->createForm(new HelpCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing HelpCategory entity.
     *
     * @Route("/{id}/update", name="dezull_help_category_update")
     * @Method("post")
     * @Template("DezullHelpBundle:HelpCategory:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HelpCategory entity.');
        }

        $editForm   = $this->createForm(new HelpCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_category_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a HelpCategory entity.
     *
     * @Route("/{id}/delete", name="dezull_help_category_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HelpCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dezull_help_category'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
