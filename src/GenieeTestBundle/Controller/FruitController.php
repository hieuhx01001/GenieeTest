<?php

namespace GenieeTestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GenieeTestBundle\Entity\Fruit;
use GenieeTestBundle\Form\FruitType;

/**
 * Fruit controller.
 *
 * @Route("/fruit")
 */
class FruitController extends Controller
{
    /**
     * Lists all Fruit entities.
     *
     * @Route("/", name="fruit_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        // init fruit form for create a fruit
        $fruit = new Fruit();
        $form = $this->createForm('GenieeTestBundle\Form\FruitType', $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruit_index');
        }


        $em = $this->getDoctrine()->getManager();

        $fruits = $em->getRepository('GenieeTestBundle:Fruit')->findAll();

        return $this->render('GenieeTestBundle:fruit:index.html.twig', array(
            'fruit' => $fruit,
            'form' => $form->createView(),
            'fruits' => $fruits,
        ));
    }

    /**
     * Creates a new Fruit entity.
     *
     * @Route("/new", name="fruit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fruit = new Fruit();
        $form = $this->createForm('GenieeTestBundle\Form\FruitType', $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruit_index', array('id' => $fruit->getId()));
        }

        return $this->render('GenieeTestBundle:fruit:new.html.twig', array(
            'fruit' => $fruit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Fruit entity.
     *
     * @Route("/{id}", name="fruit_show")
     * @Method("GET")
     */
    public function showAction(Fruit $fruit)
    {
        $deleteForm = $this->createDeleteForm($fruit);

        return $this->render('GenieeTestBundle:fruit:show.html.twig', array(
            'fruit' => $fruit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Fruit entity.
     *
     * @Route("/{id}/edit", name="fruit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Fruit $fruit)
    {
        $deleteForm = $this->createDeleteForm($fruit);
        $editForm = $this->createForm('GenieeTestBundle\Form\FruitType', $fruit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruit_index', array('id' => $fruit->getId()));
        }

        return $this->render('GenieeTestBundle:fruit:edit.html.twig', array(
            'fruit' => $fruit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Fruit entity.
     *
     * @Route("/{id}", name="fruit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Fruit $fruit)
    {
        $form = $this->createDeleteForm($fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fruit);
            $em->flush();
        }

        return $this->redirectToRoute('fruit_index');
    }

    /**
     * Creates a form to delete a Fruit entity.
     *
     * @param Fruit $fruit The Fruit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fruit $fruit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fruit_delete', array('id' => $fruit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
