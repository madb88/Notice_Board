<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Comments;
use AppBundle\Form\CommentsType;
use AppBundle\Entity\Notice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



/**
 * Comments controller.
 *
 * @Route("/comments")
 */
class CommentsController extends Controller
{

    /**
     * Lists all Comments entities.
     *
     * @Route("/", name="comments")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comments')->findAll();

        return array(
            'comments' => $comments,
        );
    }
    /**
     * Creates a new Comments entity.
     *
     * @Route("/{noticeId}", name="comments_create")
     * @Method("POST")
     * @Template("AppBundle:Comments:new.html.twig")
     * @ParamConverter("notice", class="AppBundle:Notice", options={"id" = "noticeId"})

     */
    public function createAction(Notice $notice, Request $request)
    {
        $comment = new Comments();
        
        $user = $this->getUser();
        $comment->setUser($user);
        $comment->setNotice($notice);
        $comment->setCreationDate(new \DateTime());
        
        $form = $this->createCreateForm($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $notice->addComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->persist($notice);
            $em->flush();

            return $this->redirect($this->generateUrl('notice_show', array('id' => $notice->getId())));
        }

        return array(
            'notice'=> $notice,
            'comment' => $comment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Comments entity.
     *
     * @param Comments $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comments $comment)
    {
        $form = $this->createForm(new CommentsType(), $comment, array(
            'action' => $this->generateUrl('comments_create', array('noticeId'=>$comment->getNotice()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Comments entity.
     *
     * @Route("/new/{noticeId}", name="comments_new")
     * @Method("GET")
     * @Template()
     * @ParamConverter("notice", class="AppBundle:Notice", options={"id" = "noticeId"})

     */
    public function newAction(Notice $notice)
    {
        $comment = new Comments();
        $comment->setNotice($notice);

        $form   = $this->createCreateForm($comment);

        return array(
            'notice'=> $notice,
            'comment' => $comment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Comments entity.
     *
     * @Route("/{id}", name="comments_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('AppBundle:Comments')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comments entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'comment'      => $comment,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comments entity.
     *
     * @Route("/{id}/edit", name="comments_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('AppBundle:Comments')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comments entity.');
        }

        $editForm = $this->createEditForm($comment);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'comment'      => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Comments entity.
    *
    * @param Comments $comment The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comments $comment)
    {
        $form = $this->createForm(new CommentsType(), $comment, array(
            'action' => $this->generateUrl('comments_update', array('id' => $comment->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Comments entity.
     *
     * @Route("/{id}", name="comments_update")
     * @Method("PUT")
     * @Template("AppBundle:Comments:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('AppBundle:Comments')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comments entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($comment);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comments_edit', array('id' => $id)));
        }

        return array(
            'comment'      => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Comments entity.
     *
     * @Route("/{id}", name="comments_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('AppBundle:Comments')->find($id);

            if (!$comment) {
                throw $this->createNotFoundException('Unable to find Comments entity.');
            }

            $em->remove($comment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comments'));
    }

    /**
     * Creates a form to delete a Comments entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comments_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
}
