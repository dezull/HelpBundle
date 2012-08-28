<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/{categoryId}/new", name="dezull_help_topic_new")
     * @Template()
     */
    public function newAction($categoryId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('DezullHelpBundle:HelpCategory')->find($categoryId);
        if (!$category) {
            throw $this->createNotFoundException();
        }

        $entity = new HelpTopic();
        $entity->setCategory($category);
        $form = $this->createForm($this->getForm(), $entity);

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

            $categoryId = $entity->getCategory()->getId();

            $em->remove($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_category_edit', array(
                'id' => $categoryId,
            )));
        }

        return $this->redirect($this->generateUrl('dezull_help_category'));
    }

    /**
     * Handles image upload
     *
     * @Route("/upload-image", name="dezull_help_topic_image_upload")
     */
    public function uploadImageAction(Request $request)
    {
        $funcNum = $request->query->get('CKEditorFuncNum');
        $CKEditor = $request->query->get('CKEditor');
        $langCode = $request->query->get('langCode');

        $uploaded = $request->files->get('upload', null);
        $path = '';

        // Client-visible message
        $message = '';

        if ($uploaded === null) {
            $message = 'File not uploaded';
        } else {
            try {
                $path = $this->handleUploadedImage($uploaded);
            } catch(\Exception $e) {
                $message = 'Could not upload the image';
            }
        }

        return new Response("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$path', '$message');</script>");
    }

    private function handleUploadedImage($uploaded)
    {
        $c = $this->container;

        $uploadDir = $c->getParameter('dezull_help.image.dir');
        if (!\file_exists($uploadDir)) {
            \mkdir($uploadDir, 0755, true);
        }

        $mime = $uploaded->getMimeType();
        $acceptableMimes = $c->getParameter('dezull_help.image.mimetypes');
        if (!in_array($mime, $acceptableMimes)) {
            throw new \Exception("Invalid MIME type: $mime");
        }

        $hash = \md5_file($uploaded->getRealPath());
        $fileName = $hash . '.' . preg_replace("/^.+\//", '', $mime);
        try {
            $uploaded->move($uploadDir, $fileName);
        } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {
            throw $e;
        }

        return $c->getParameter('dezull_help.image.baseurl') . "/$fileName";
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
