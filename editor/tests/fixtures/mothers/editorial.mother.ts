import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { AddressMother } from '@tests/fixtures/mothers/address.mother';
import { EnterpriseContactMother } from '@tests/fixtures/mothers/enterprise-contact.mother';
import { Editorial } from '@/editorials/model/editorial';

const ANAYA = {
  id: '67706e7d-c0a5-4dc0-bea2-4cef102d602f',
  name: 'Anaya',
  comercialName: 'Anaya Inc.',
  contact: EnterpriseContactMother.anaya,
  address: AddressMother.anytown,
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type EditorialFixture = typeof ANAYA;

export class EditorialMother extends BaseMother {
  static anaya(this: void, overrides?: Partial<Editorial>): Editorial {
    return EditorialMother.create(ANAYA, overrides);
  }

  protected static create(
    values: EditorialFixture,
    overrides?: Partial<Editorial>,
  ) {
    const fields: Editorial = EditorialMother.merge<
      EditorialFixture,
      Editorial
    >(values, overrides);

    return Editorial.from(fields);
  }
}
