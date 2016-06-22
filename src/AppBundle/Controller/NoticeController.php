<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Notice;
use AppBundle\Form\NoticeType;

/**
 * Notice controller.
 *
 * @Route("/notice")
 */
class NoticeController extends Controller
{

    /**
     * Lists all Notice entities.
     *
     * @Route("/notice", name="notice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Notice')->findAll();

        return array(
            'entities' => $entities,
        );
        
    }
    /**
     * Creates a new Notice entity.
     *
     * @Route("/", name="notice_create")
     * @Method("POST")
     * @Template("AppBundle:Notice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $notice = new Notice();
        $user = $this->getUser();
        $user->addNotice($notice);
        $notice->setUser($user);
        $notice->setCreationDate(new \DateTime());
         
        $form = $this->createCreateForm($notice);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($notice);
            $em->flush();

            return $this->redirect($this->generateUrl('notice_show', array('id' => $notice->getId())));
        }

        return array(
            'notices' => $notice,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Notice entity.
     *
     * @param Notice $notice The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Notice $notice)
    {
        $form = $this->createForm(new NoticeType(), $notice, array(
            'action' => $this->generateUrl('notice_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Notice entity.
     *
     * @Route("/new", name="notice_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $notice = new Notice();
        $form   = $this->createCreateForm($notice);

        return array(
            'notice' => $notice,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Notice entity.
     *
     * @Route("/{id}", name="notice_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $notice = $em->getRepository('AppBundle:Notice')->find($id);

        if (!$notice) {
            throw $this->createNotFoundException('Unable to find Notice.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'notice'      => $notice,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Notice entity.
     *
     * @Route("/{id}/edit", name="notice_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $notice = $em->getRepository('AppBundle:Notice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notice entity.');
        }

        $editForm = $this->createEditForm($notice);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'notice'      => $notice,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Notice entity.
    *contacts
    * @param Notice $notice The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Notice $notice)
    {
        $form = $this->createForm(new NoticeType(), $notice, array(
            'action' => $this->generateUrl('notice_update', array('id' => $notice->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Notice entity.
     *
     * @Route("/{id}", name="notice_update")
     * @Method("PUT")
     * @Template("AppBundle:Notice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $notice = $em->getRepository('AppBundle:Notice')->find($id);

        if (!$notice) {
            throw $this->createNotFoundException('Unable to find Notice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($notice);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('notice_edit', array('id' => $id)));
        }

        return array(
            'notice'      => $notice,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Notice entity.
     *
     * @Route("/{id}", name="notice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $notice = $em->getRepository('AppBundle:Notice')->find($id);

            if (!$notice) {
                throw $this->createNotFoundException('Unable to find Notice entity.');
            }

            $em->remove($notice);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('notice'));
    }

    /**
     * Creates a form to delete a Notice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notice_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    


}
