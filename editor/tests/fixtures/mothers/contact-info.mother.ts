import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { ContactInfo } from '@/customers/model/contact-info';

const DOE_CONTACT_INFO = {
  email: 'johndoe@gmail.com',
  phone1: '+1234567890',
  phone2: '+0987654321',
};

type ContactInfoFixture = typeof DOE_CONTACT_INFO;

export class ContactInfoMother extends BaseMother {
  static doe(this: void, overrides?: Partial<ContactInfo>): ContactInfo {
    return ContactInfoMother.create(DOE_CONTACT_INFO, overrides);
  }

  protected static create(
    values: ContactInfoFixture,
    overrides?: Partial<ContactInfo>,
  ): ContactInfo {
    const fields = ContactInfoMother.merge<ContactInfoFixture, ContactInfo>(
      values,
      overrides,
    );

    return ContactInfo.from(fields);
  }
}
