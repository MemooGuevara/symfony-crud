<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

        if (!$product) {
            $this->addFlash('error', 'The product does not exist');
            return $this->redirectToRoute('products_index');
        }

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

        if (!$product) {
            $this->addFlash('error', 'The product does not exist');
            return $this->redirectToRoute('products_index');
        }

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

        if (!$product) {
            $this->addFlash('error', 'The product does not exist');
            return $this->redirectToRoute('products_index');
        }

        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product deleted successfully');
        return $this->redirectToRoute('products_index');
    }

    /**
     * @Route("/pdf", name="products_pdf")
     */
    public function pdf(): Response
    {
        $spreadsheet = new Spreadsheet();

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Products");
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'CODE');
        $sheet->setCellValue('C1', 'NAME');
        $sheet->setCellValue('D1', 'BRAND');
        $sheet->setCellValue('E1', 'PRICE');
        $sheet->setCellValue('F1', 'DESCRIPTION');
        $sheet->setCellValue('G1', 'CATEGORY NAME');
        $sheet->setCellValue('H1', 'CREATED AT');
        $sheet->setCellValue('I1', 'UPDATED AT');

        $count = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $count, $product->getId());
            $sheet->setCellValue('B' . $count, $product->getCode());
            $sheet->setCellValue('C' . $count, $product->getName());
            $sheet->setCellValue('D' . $count, $product->getBrand());
            $sheet->setCellValue('E' . $count, $product->getPrice());
            $sheet->setCellValue('F' . $count, $product->getDescription());
            $sheet->setCellValue('G' . $count, $product->getCategory()->getName());
            $sheet->setCellValue('H' . $count, $product->getCreatedAt());
            $sheet->setCellValue('I' . $count, $product->getUpdatedAt());
            $count++;
        }


        $writer = new Xlsx($spreadsheet);

        $fileName = 'products.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
