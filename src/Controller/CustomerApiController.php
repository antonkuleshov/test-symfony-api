<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
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
     * @return JsonResponse
     */
    public function getCustomers(CustomerRepository $customerRepository): JsonResponse
    {
        return new JsonResponse($customerRepository->findAll(), Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="get_customer", methods={"GET"})
     * @return JsonResponse
     */
    public function getCustomer(Customer $customer): JsonResponse
    {
        return new JsonResponse($customer, Response::HTTP_OK, []);
    }

    /**
     * @Route("/", name="create_customer", methods={"POST"})
     * @return JsonResponse
     */
    public function createCustomer(): JsonResponse
    {
        $customer = new Customer();

        return new JsonResponse($customer, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     * @return JsonResponse
     */
    public function updateCustomer(Customer $customer): JsonResponse
    {
        return new JsonResponse($customer, Response::HTTP_OK, []);
    }

    /**
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     * @return JsonResponse
     */
    public function deleteCustomer(Customer $customer): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($customer);
        $entityManager->flush();

        return new JsonResponse("Customer deleted", Response::HTTP_OK, []);
    }
}