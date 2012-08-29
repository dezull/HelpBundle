<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new HelpCategoryType(), new HelpCategory());
        $entities = $em->getRepository('DezullHelpBundle:HelpCategory')->findAll();

        return array(
            'entities' => $entities,
            'form' => $form->createView(),
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
        $category  = new HelpCategory();
        $request = $this->getRequest();
        $form = $this->createForm(new HelpCategoryType(), $category);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->setFlash('notice', $category->getName() . ' created');
        } else {
            $this->get('session')->setFlash('error', 'Error creating ' . $category->getName());
        }

        return $this->redirect($this->generateUrl('dezull_help_category'));
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

        $category = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find HelpCategory entity.');
        }

        $editForm = $this->createForm(new HelpCategoryType(), $category);
        $deleteForm = $this->createDeleteForm($id);

        $topics = $em->getRepository('DezullHelpBundle:HelpTopic')->findByCategory($id);

        return array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'topics' => $topics,
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

        $category = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find HelpCategory entity.');
        }

        $editForm   = $this->createForm(new HelpCategoryType(), $category);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_category_edit', array('id' => $id)));
        }

        return array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
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
            $category = $em->getRepository('DezullHelpBundle:HelpCategory')->find($id);

            if (!$category) {
                throw $this->createNotFoundException('Unable to find HelpCategory entity.');
            }

            // Delete contained topics
            $topics = $em->getRepository('DezullHelpBundle:HelpTopic')
                ->findByCategory($id);
            foreach ($topics as $topic) {
                $em->remove($topic);
            }
            
            $em->remove($category);
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
