import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { Address } from '@/shared/models/address';

const ANYTOWN = {
  street: '123 Main Street',
  postalCode: '12345',
  city: 'Anytown',
  province: 'Alaska',
  country: 'EEUU',
};

type AddressFixture = typeof ANYTOWN;

export class AddressMother extends BaseMother {
  static anytown(this: void, overrides?: Partial<Address>): Address {
    return AddressMother.create(ANYTOWN, overrides);
  }

  protected static create(
    values: AddressFixture,
    overrides?: Partial<Address>,
  ): Address {
    const fields: Address = AddressMother.merge<AddressFixture, Address>(
      values,
      overrides,
    );
    return Address.from(fields);
  }
}
