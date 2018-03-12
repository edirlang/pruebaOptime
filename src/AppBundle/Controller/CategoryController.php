<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("categorias")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/unActive", name="category_unActive")
     * @Method("GET")
     */
    public function unActiveCategoryAction($id){
        $em = $this->getDoctrine()->getManager();
        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $category->setActive(false);
        $em->persist($category);
        $em->flush();
        return $this->redirectToRoute('category_index');
    }

    /**
     * @Route("/{id}/active", name="category_active")
     * @Method("GET")
     */
    public function activeCategoryAction($id){
        $em = $this->getDoctrine()->getManager();
        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $category->setActive(true);
        $em->persist($category);
        $em->flush();
        return $this->redirectToRoute('category_index');
    }
}
