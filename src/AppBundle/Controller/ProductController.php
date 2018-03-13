<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("productos")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/lista/{index}/{code}/{name}/{category}", name="product_index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request, $index = 1, $code = '', $name = '', $category = '')
    {
        $form = $this->createFormFilters();
        $form->handleRequest($request);

        $fileters = [
            'code' => $code,
            'name' => $name,
            'category' => $category
        ];

        if($form->isValid()){
            if($this->checkFilterChange($fileters, $form)){
                $index = 1;
            }
            $this->uploadFormFilters($form,$fileters);
            $code = $fileters['code'];
            $name = $fileters['name'];
            $category = $fileters['category'];
        }else{
            $this->uploadFiltersForm($form, $fileters);
        }
        $resultFilterAndPagination = $this->container->get(ProductService::class)->filtersProducts($fileters, $index);

        return $this->render('product/index.html.twig', array(
            'products' => $resultFilterAndPagination['products'],
            'max_index' => $resultFilterAndPagination['max_index'],
            'index' => $index,
            'filters' => $fileters,
            'form_filter' => $form->createView()
        ));
    }

    private function createFormFilters(){
        $form = $this->get("form.factory")->createNamedBuilder('filter')
            ->add("code", TextType::class,[
                'label' => 'Codigo',
                'required' => false,
                'label_attr' => ['class' => ''],
                'attr' => ['class' => 'form-control','placeholder' => 'Codigo']
                ])
            ->add("name", TextType::class,[
                'label' => 'Nombre',
                'required' => false,
                'label_attr' => ['class' => ''],
                'attr' => ['class' => 'form-control','placeholder' => 'Nombre']
            ])
            ->add("category", TextType::class,[
                'label' => 'Categoria',
                'required' => false,
                'label_attr' => ['class' => ''],
                'attr' => ['class' => 'form-control','placeholder' => 'Categoria']
            ])
            ->getForm();

        return $form;
    }

    private function uploadFormFilters($form, &$filters){
        $filters = [
            'code' => $form->get('code')->getData(),
            'name' => $form->get('name')->getData(),
            'category' => $form->get('category')->getData()
        ];
        return $filters;
    }

    private function checkFilterChange($filters, $form){
        if($filters['code'] != $form->get('code')->getData()){
            return true;
        }
        if($filters['name'] != $form->get('name')->getData()){
            return true;
        }
        if($filters['category'] != $form->get('category')->getData()){
            return true;
        }
        return false;
    }

    private function uploadFiltersForm(&$form, $filters){
        $form->get('code')->setData($filters['code']);
        $form->get('name')->setData($filters['name']);
        $form->get('category')->setData($filters['category']);

        return $form;
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="productos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id = null)
    {
        $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['success' => true]);
    }


}
