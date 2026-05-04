import { CustomerDescriptor } from '@/customers/model/customer-descriptor';

describe('CustomerDescriptor', () => {
  it('should create with full name', () => {
    const descriptor = new CustomerDescriptor(
      '1',
      'John',
      'Doe',
      'VAT123',
      'NUM123',
    );

    expect(descriptor.fullName).toBe('John Doe');
  });

  it('should create with only name', () => {
    const descriptor = new CustomerDescriptor(
      '1',
      'John',
      '',
      'VAT123',
      'NUM123',
    );

    expect(descriptor.fullName).toBe('John');
  });
});
