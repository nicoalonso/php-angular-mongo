import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Customer } from '@/customers/model/customer';
import { CustomerMother } from '@tests/fixtures/mothers/customer.mother';
import { Ref } from '@tests/fixtures/ref';

export class CustomerServiceStub extends EntityServiceStub<Customer> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const johnDoe = CustomerMother.johnDoe();
    this.addFixture(Ref.CustomerJohnDoe, johnDoe);
  }
}
