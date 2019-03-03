<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/products")
 */
class ProductApiController extends AbstractController
{
    /**
     * @Route("/", name="get_Products", methods={"GET"})
     * @return JsonResponse
     */
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        return new JsonResponse($productRepository->findAll(), Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="get_Product", methods={"GET"})
     * @return JsonResponse
     */
    public function getProduct(Product $product): JsonResponse
    {
        return new JsonResponse($product, Response::HTTP_OK, []);
    }

    /**
     * @Route("/", name="create_Product", methods={"POST"})
     * @return JsonResponse
     */
    public function createProduct(): JsonResponse
    {
        $product = new Product();

        return new JsonResponse($product, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="update_Product", methods={"PUT"})
     * @return JsonResponse
     */
    public function updateProduct(Product $product): JsonResponse
    {
        return new JsonResponse($product, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="delete_Product", methods={"DELETE"})
     * @return JsonResponse
     */
    public function deleteProduct(Product $product): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse("Product deleted", Response::HTTP_OK, []);
    }
}