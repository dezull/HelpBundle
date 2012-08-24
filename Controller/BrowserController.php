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
 * Help Browser controller.
 */
class BrowserController extends Controller
{
    /**
     * Lists all HelpTopic entities.
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

    /**
     * Handles image upload
     *
     * @Route("/upload-image", name="dezull_help_image_upload")
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
}
