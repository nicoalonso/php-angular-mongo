import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { AddressMother } from '@tests/fixtures/mothers/address.mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { MembershipMother } from '@tests/fixtures/mothers/membership.mother';
import { ContactInfoMother } from '@tests/fixtures/mothers/contact-info.mother';
import { Customer } from '@/customers/model/customer';
import { CustomerDescriptor } from '@/customers/model/customer-descriptor';

const JOHN_DOE = {
  id: '972827c0-5cbb-4c68-9ccf-82c98ecc3313',
  name: 'John',
  surname: 'Doe',
  membership: MembershipMother.active,
  contact: ContactInfoMother.doe,
  address: AddressMother.anytown,
  vatNumber: '12345667A',
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type CustomerFixture = typeof JOHN_DOE;

export class CustomerMother extends BaseMother {
  static johnDoe(this: void, overrides?: Partial<Customer>): Customer {
    return CustomerMother.create(JOHN_DOE, overrides);
  }

  protected static create(
    values: CustomerFixture,
    overrides?: Partial<Customer>,
  ): Customer {
    const fields: Customer = CustomerMother.merge<CustomerFixture, Customer>(
      values,
      overrides,
    );
    return Customer.from(fields);
  }
}

export class CustomerDescriptorMother {
  static johnDoe(
    this: void,
    overrides?: Partial<Customer>,
  ): CustomerDescriptor {
    const customer = CustomerMother.johnDoe(overrides);
    return new CustomerDescriptor(
      customer.id,
      customer.name,
      customer.surname,
      customer.vatNumber,
      customer.membership.number,
    );
  }
}
