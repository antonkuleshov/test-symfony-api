<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\CustomerService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/customers")
 */
class CustomerApiController extends AbstractController
{
    /**
     * @Route("/", name="get_customers", methods={"GET"})
     * @param CustomerRepository $customerRepository
     * @return JsonResponse
     */
    public function getCustomers(CustomerRepository $customerRepository): JsonResponse
    {
        $customers = $this->get("serializer")->serialize(
            $customerRepository->findAll(),
            'json'
        );

        return new JsonResponse($customers, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="get_customer", methods={"GET"})
     * @param Customer $customer
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function getCustomer(Customer $customer, ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        if(!$customer) {
            $status = JsonResponse::HTTP_NOT_FOUND;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_NOT_FOUND, "Not found this uuid"
            );
            return new JsonResponse($data, $status);
        }

        $json = $this->get("serializer")->serialize($customer, 'json');

        return new JsonResponse($json, Response::HTTP_OK, []);
    }

    /**
     * @Route("/", name="create_customer", methods={"POST"})
     * @param Request $request
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function createCustomer(
        Request $request,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {

        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['first_name']) || !isset($data['last_name'])) {

            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $customerService->createCustomer($data);

        if ($result instanceof Customer) {

            $status = JsonResponse::HTTP_CREATED;
            $data = [
                'data' => [
                    'uuid' => $result->getUuid(),
                    'first_name' => $result->getFirstName(),
                    'last_name' => $result->getLastName(),
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
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     * @param Customer $customer
     * @param Request $request
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function updateCustomer(
        Customer $customer,
        Request $request,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (is_null($data) || !isset($data['first_name']) || !isset($data['last_name'])) {

            $status = JsonResponse::HTTP_BAD_REQUEST;
            $data = $errorDecorator->decorateError(
                JsonResponse::HTTP_BAD_REQUEST, "Invalid JSON format"
            );

            return new JsonResponse($data, $status);
        }

        $result = $customerService->updateCustomer($customer, $data);

        if ($result instanceof Customer) {

            $status = JsonResponse::HTTP_OK;
            $data = [
                'data' => [
                    'uuid' => $result->getUuid(),
                    'first_name' => $result->getFirstName(),
                    'last_name' => $result->getLastName(),
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
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     * @param Customer $customer
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return JsonResponse
     */
    public function deleteCustomer(
        Customer $customer,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): JsonResponse
    {
        $result = $customerService->deleteCustomer($customer);

        if ($result instanceof Customer) {
            $status = JsonResponse::HTTP_LOCKED;
            $data = [
                'data' => [
                    'uuid' => $result->getUuid(),
                    'first_name' => $result->getFirstName(),
                    'last_name' => $result->getLastName(),
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