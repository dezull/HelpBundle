<?php

namespace Dezull\Bundle\HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dezull\Bundle\HelpBundle\Entity\HelpTopic;
use Dezull\Bundle\HelpBundle\Form\HelpTopicType;

/**
 * HelpTopic controller.
 */
class HelpTopicController extends Controller
{
    /**
     * Finds and displays a HelpTopic entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $topic = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DezullHelpBundle:HelpTopic:show.html.twig', array(
            'topic' => $topic,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new HelpTopic entity.
     */
    public function newAction($categoryId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('DezullHelpBundle:HelpCategory')->find($categoryId);
        if (!$category) {
            throw $this->createNotFoundException();
        }

        $topic = new HelpTopic();
        $topic->setCategory($category);
        $form = $this->createForm($this->getForm(), $topic);

        return $this->render('DezullHelpBundle:HelpTopic:new.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a new HelpTopic entity.
     */
    public function createAction()
    {
        $topic = new HelpTopic();

        $request = $this->getRequest();
        $form = $this->createForm($this->getForm(), $topic);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();

            $repo = $em->getRepository('DezullHelpBundle:HelpTopic');
            $topic->setSequence($repo->getMaxSequenceByCategory($topic->getCategory()) + 1);

            $em->persist($topic);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_topic_show', array('id' => $topic->getId())));
        }

        return $this->render('DezullHelpBundle:HelpTopic:new.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing HelpTopic entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $topic = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $editForm = $this->createForm($this->getForm(), $topic);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DezullHelpBundle:HelpTopic:edit.html.twig', array(
            'topic' => $topic,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing HelpTopic entity.
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $topic = $em->getRepository('DezullHelpBundle:HelpTopic')->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('Unable to find HelpTopic entity.');
        }

        $editForm = $this->createForm($this->getForm(), $topic);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($topic);
            $em->flush();

            return $this->redirect($this->generateUrl('dezull_help_topic_edit', array('id' => $id)));
        }

        return $this->render('DezullHelpBundle:HelpTopic:edit.html.twig', array(
            'topic' => $topic,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HelpTopic entity.
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


    /**
     * Update topic sequences
     */
    public function updateSequencesAction(Request $request, $categoryId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if (!$em->getRepository('DezullHelpBundle:HelpCategory')->find($categoryId)) {
            throw $this->createNotFoundException();
        }

        $sequences = $request->request->get('sequence');
        if (!is_array($sequences)) {
            return $this->redirect($this->generateUrl('dezull_help_category_edit', array(
                'id' => $categoryId,
            )));
        }

        $repo = $em->getRepository('DezullHelpBundle:HelpTopic');

        foreach ($sequences as $topicId => $sequence) {
            $topic = $repo->find($topicId);
            if (!$topic) continue;

            $topic->setSequence((int) $sequence);
            $em->persist($topic);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('dezull_help_category_edit', array(
            'id' => $categoryId,
        )));
    }

    /**
     * List topic by category Id
     */
    public function listByCategoryAction(Request $request, $categoryId)
    {
        $topics = $this->getDoctrine()->getEntityManager()
            ->getRepository('DezullHelpBundle:HelpTopic')
            ->findByCategoryOrderBySequence($categoryId);

        return $this->render('DezullHelpBundle:HelpTopic:_list.html.twig', array(
            'categoryId' => $categoryId,
            'topics' => $topics,
        ));
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
