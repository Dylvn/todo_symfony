<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TodoList;
use AppBundle\Form\TodoListType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/todo_list")
 */
class TodoListController extends Controller
{
    /**
     * @Route("/", name="todo_list_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $todolists = $em->getRepository('AppBundle:TodoList')->findBy(array(
            'user' => $this->getUser()->getId(),
        ));

        return $this->render('TodoList/index.html.twig', array(
            'todolists' => $todolists,
        ));
    }

    /**
     * @Route("/create", name="todo_list_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $todoList = new TodoList();
        $todoList->setUser($this->getUser());

        $form = $this->createForm(TodoListType::class, $todoList);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($todoList);
            $em->flush();

            $this->addFlash('success', 'Votre tâche à été créé');
            return $this->redirectToRoute('todo_list_index');
        }

        return $this->render('TodoList/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update/{todoList}", name="todo_list_update")
     */
    public function updateAction(Request $request, TodoList $todoList)
    {

        $this->denyAccessUnlessGranted('view', $todoList);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(TodoListType::class, $todoList);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Votre tâche à été modifié');

            if ($form->get('delete')->isClicked()) {
                $em->remove($todoList);
                $this->addFlash('success', 'Votre tâche à été supprimé');
            }

            $em->flush();

            return $this->redirectToRoute('todo_list_index');
        }

        return $this->render('TodoList/update.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
