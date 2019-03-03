<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create Customer by given data
     *
     * @param $data array which contains information about Customer
     *    $data = [
     *      'first_name' => (string) Customer first name. Required.
     *      'last_name' => (string) Customer last name. Required.
     *      'date_of_birth' => (date) Customer date of birth. Non-required.
     *      'status' => (string) Customer status. Required.
     *    ]
     * @return Customer|string Customer or error message
     */
    public function createCustomer(array $data)
    {
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['status'])) {
            return "First Name, Last Name and status must be provided to create new Customer";
        }

        try {

            $customer = new Customer();
            $customer->setFirstName($data['first_name']);
            $customer->setLastName($data['last_name']);
            $customer->setStatus($data['status']);

            if (isset($data['date_of_birth'])) {
                $customer->setDateOfBirth($data['date_of_birth']);
            }

            $customer->setCreatedAt(new \DateTime());
            $customer->setUpdatedAt(new \DateTime());

            $this->em->persist($customer);
            $this->em->flush();

            return $customer;
        } catch (\Exception $ex) {
            return "Unable to create Customer";
        }
    }

    /**
     * Update Customer by given data
     *
     * @param Customer $customer
     * @param $data array which contains information about Customer
     *    $data = [
     *      'first_name' => (string) Customer first name. Required.
     *      'last_name' => (string) Customer last name. Required.
     *      'date_of_birth' => (date) Customer date of birth. Non-required.
     *      'status' => (string) Customer status. Required.
     *    ]
     * @return Customer|string Customer or error message
     */
    public function updateCustomer(Customer $customer, array $data)
    {
        try {

            if (isset($data['first_name'])) {
                $customer->setFirstName($data['first_name']);
            }

            if (isset($data['last_name'])) {
                $customer->setLastName($data['last_name']);
            }

            if (isset($data['status'])) {
                $customer->setStatus($data['status']);
            }

            if (isset($data['date_of_birth'])) {
                $customer->setDateOfBirth($data['date_of_birth']);
            }

            $this->em->persist($customer);
            $this->em->flush();

            return $customer;

        }  catch (\Exception $ex) {
            return "Unable to update Customer";
        }
    }

    /**
     * @param Customer $customer
     * @return Customer|string Customer or error message
     */
    public function deleteCustomer(Customer $customer)
    {
        try {
            $customer->setStatus("deleted");
            $customer->setDeletedAt(new \DateTime());

            $this->em->persist($customer);
            $this->em->flush();

            return $customer;

        } catch (\Exception $ex) {
            return "Unable to remove Customer";
        }
    }
}