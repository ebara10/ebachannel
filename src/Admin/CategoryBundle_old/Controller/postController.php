<?php

namespace Admin\CategoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Admin\CategoryBundle\Entity\post;
use Admin\CategoryBundle\Form\postType;

/**
 * post controller.
 *
 */
class postController extends Controller
{

    /**
     * Lists all post entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminCategoryBundle:post')->findAll();
        return $this->render('AdminCategoryBundle:post:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new post entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new post();
		//空のエンティティからフォームを作成する
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('post_show', array('id' => $entity->getId())));
        }

        return $this->render('AdminCategoryBundle:post:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a post entity.
     *
     * @param post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(post $entity)
    {
        $form = $this->createForm(new postType(), $entity, array(
            'action' => $this->generateUrl('post_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new post entity.
     *
     */
    public function newAction()
    {
        $entity = new post();
        $form   = $this->createCreateForm($entity);

        return $this->render('AdminCategoryBundle:post:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AdminCategoryBundle:post:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AdminCategoryBundle:post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a post entity.
    *
    * @param post $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(post $entity)
    {
        $form = $this->createForm(new postType(), $entity, array(
            'action' => $this->generateUrl('post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing post entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('post_edit', array('id' => $id)));
        }

        return $this->render('AdminCategoryBundle:post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a post entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminCategoryBundle:post')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find post entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->render('AdminCategoryBundle:post:delete.html.twig', array('id' => $id));
    }
    /**
     * Creates a form to delete a post entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
