<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="products_index")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $request->query->get('filter', '');
        $query = $em->getRepository(Product::class)->getPaginate($filter);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );
        
        return $this->render(
            'product/index.html.twig',
            array(
                'products' => $pagination,
                'filter' => $filter
            )
        );
    }

    /**
     * @Route("/new", name="products_new")
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product created successfully');
            return $this->redirectToRoute('products_index');
        }

        return $this->render(
            'product/new.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/show/{id}", name="products_show")
     */
    public function show(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        return $this->render(
            'product/show.html.twig',
            array(
                'product' => $product
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="products_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product created successfully');
            return $this->redirectToRoute('products_index');
        }

        return $this->render(
            'product/edit.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/delete/{id}", name="products_delete")
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product deleted successfully');
        return $this->redirectToRoute('products_index');
    }
}
