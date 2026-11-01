<?php

namespace TremulantTech\Veeqo\Resources;

use Generator;
use LogicException;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\Customers\CreateCustomerData;
use TremulantTech\Veeqo\Data\Customers\UpdateCustomerData;
use TremulantTech\Veeqo\Enums\CustomerType;
use TremulantTech\Veeqo\Requests\Customers\CreateCustomer;
use TremulantTech\Veeqo\Requests\Customers\GetCustomer;
use TremulantTech\Veeqo\Requests\Customers\ListCustomers;
use TremulantTech\Veeqo\Requests\Customers\UpdateCustomer;
use TremulantTech\Veeqo\Responses\Customer;
use TremulantTech\Veeqo\Veeqo;

class Customers
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, Customer>
     */
    public function list(?string $query = null, ?CustomerType $customerType = null): Generator
    {
        foreach ($this->veeqo->paginate(new ListCustomers($query, $customerType)) as $page) {
            if (! $page instanceof Response) {
                throw new LogicException('Expected a synchronous page');
            }
            foreach ($this->veeqo->hydrateList(Customer::class, $page) as $customer) {
                yield $customer;
            }
        }
    }

    public function get(int $id): Customer
    {
        return $this->veeqo->hydrate(Customer::class, $this->veeqo->send(new GetCustomer($id)));
    }

    public function create(CreateCustomerData $data): Customer
    {
        return $this->veeqo->hydrate(Customer::class, $this->veeqo->send(new CreateCustomer($data)));
    }

    public function update(int $id, UpdateCustomerData $data): Customer
    {
        return $this->veeqo->hydrate(Customer::class, $this->veeqo->send(new UpdateCustomer($id, $data)));
    }
}
