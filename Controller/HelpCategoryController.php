<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Dezull\Bundle\HelpBundle\Entity\HelpCategory;
use Dezull\Bundle\HelpBundle\Form\HelpCategoryType;

/**
 * HelpCategory controller.
 */
class HelpCategoryController extends Controller
{
    /**
     * Lists all HelpCategory entities.
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new HelpCategoryType(), new HelpCategory());
        $entities = $em->getRepository('DezullHelpBundle:HelpCategory')->findAllOrderBySequence();

        return $this->render('DezullHelpBundle:HelpCategory:index.html.twig', array(
            'entities' => $entities,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new HelpCategory entity.
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category  = new HelpCategory();
        $repo = $em->getRepository('DezullHelpBundle:HelpCategory');
        $category->setSequence($repo->getMaxSequence() + 1);

        $form = $this->createForm(new HelpCategoryType(), $category);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_category'));
        }

        $entities = $repo->findAll();

        return $this->render('DezullHelpBundle:HelpCategory:index.html.twig', array(
            'entities' => $entities,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing HelpCategory entity.
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

        return $this->render('DezullHelpBundle:HelpCategory:edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing HelpCategory entity.
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

        return $this->render('DezullHelpBundle:HelpCategory:edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HelpCategory entity.
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

    /**
     * Update category sequences
     */
    public function updateSequencesAction(Request $request)
    {
        $sequences = $request->request->get('sequence');
        if (!is_array($sequences)) {
            return $this->redirect($this->generateUrl('dezull_help_category'));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('DezullHelpBundle:HelpCategory');

        foreach ($sequences as $categoryId => $sequence) {
            $category = $repo->find($categoryId);
            if (!$category) continue;

            $category->setSequence((int) $sequence);
            $em->persist($category);
        }

        $em->flush();

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
