<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Service\AppSerializer;
use App\Service\CustomerService;
use App\Service\ResponseErrorDecoratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customers")
 */
class CustomerController extends AbstractController
{
    private $customersApi;
    private $serializer;

    public function __construct(
        CustomerApiController $customersApi,
        AppSerializer $serializer)
    {
        $this->customersApi = $customersApi;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="customer_index", methods={"GET"})
     * @param CustomerRepository $customerRepository
     * @return Response
     */
    public function index(CustomerRepository $customerRepository): Response
    {
        $response = $this->customersApi->getCustomers($customerRepository);

        $arrayCollection = $this->serializer->deserialize($response->getContent(), 'ArrayCollection');

        return $this->render('customer/index.html.twig', [
            'customers' => $arrayCollection,
        ]);
    }

    /**
     * @Route("/new", name="customer_new", methods={"GET","POST"})
     * @param Request $request
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return Response
     */
    public function new(
        Request $request,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $jsonFormData = json_encode($request->request->get('customer'), true);

            $customer = $this->customersApi->createCustomer($jsonFormData, $customerService, $errorDecorator);

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="customer_show", methods={"GET"})
     * @param Customer $customer
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return Response
     */
    public function show(Customer $customer, ResponseErrorDecoratorService $errorDecorator): Response
    {
        $response = $this->customersApi->getCustomer($customer, $errorDecorator);

        $customerObj = $this->serializer->deserialize($response->getContent(), get_class($customer));

        return $this->render('customer/show.html.twig', [
            'customer' => $customerObj,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="customer_edit", methods={"GET","POST"})
     * @param Customer $customer
     * @param Request $request
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return Response
     */
    public function edit(
        Customer $customer,
        Request $request,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): Response
    {

        $response = $this->customersApi->getCustomer($customer, $errorDecorator);

        $customerObj = $this->serializer->deserialize($response->getContent(), get_class($customer));

        $form = $this->createForm(CustomerType::class, $customerObj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $jsonFormData = json_encode($request->request->get('customer'), true);

            $customerResponse = $this->customersApi->updateCustomer($customer, $jsonFormData, $customerService, $errorDecorator);

            return $this->redirectToRoute('customer_index', [
                'uuid' => $customer->getUuid(),
            ]);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="customer_delete", methods={"DELETE"})
     * @param Customer $customer
     * @param Request $request
     * @param CustomerService $customerService
     * @param ResponseErrorDecoratorService $errorDecorator
     * @return Response
     */
    public function delete(
        Customer $customer,
        Request $request,
        CustomerService $customerService,
        ResponseErrorDecoratorService $errorDecorator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getUuid(), $request->request->get('_token'))) {

            $response = $this->customersApi->deleteCustomer($customer, $customerService, $errorDecorator);
        }

        return $this->redirectToRoute('customer_index');
    }
}
