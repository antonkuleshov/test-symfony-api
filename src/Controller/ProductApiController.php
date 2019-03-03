<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Service\ResponseErrorDecoratorService;
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
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $json = $this->get("serializer")->serialize($productRepository->findAll(), 'json');

        return new JsonResponse($json, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="get_Product", methods={"GET"})
     * @param Product $product
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getProduct(Product $product, ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        if(!$product) {
            $status = JsonResponse::HTTP_NOT_FOUND;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_NOT_FOUND, "Not found this issn"
            );
            return new JsonResponse($data, $status);
        }

        $json = $this->get("serializer")->serialize($product, 'json');

        $productJson = stripslashes($json);

        return new JsonResponse($productJson, Response::HTTP_OK, []);
    }

    /**
     * @Route("/", name="create_product", methods={"POST"})
     * @param Request $request
     * @param ProductService $productService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function createProduct(
        Request $request,
        ProductService $productService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['name'])) {

            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $productService->createProduct($data);

        if ($result instanceof Product) {

            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'data' => [
                    'issn' => $result->getIssn(),
                    'name' => $result->getName(),
                    'status' => $result->getStatus(),
                    'created' => $result->getCreatedAt()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/{id}", name="update_product", methods={"PUT"})
     * @param Product $product
     * @param Request $request
     * @param ProductService $productService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function updateProduct(
        Product $product,
        Request $request,
        ProductService $productService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['name'])) {

            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $productService->updateProduct($product, $data);

        if ($result instanceof Product) {

            $status = JsonResponse::HTTP_OK;
            $data = [
                'data' => [
                    'issn' => $result->getIssn(),
                    'name' => $result->getName(),
                    'status' => $result->getStatus(),
                    'updated' => $result->getUpdatedAt()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }

        return new JsonResponse($data, $status);
    }

    /**
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     * @param Product $product
     * @param ProductService $productService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function deleteProduct(
        Product $product,
        ProductService $productService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        $result = $productService->deleteProduct($product);

        if ($result instanceof Product) {
            $status = JsonResponse::HTTP_LOCKED;
            $data = [
                'data' => [
                    'issn' => $result->getIssn(),
                    'name' => $result->getName(),
                    'status' => $result->getStatus(),
                    'deleted' => $result->getDeletedAt()
                ]
            ];
        } else {
            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError($status, $result);
        }
        return new JsonResponse($data, $status);
    }
}