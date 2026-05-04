import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { EnterpriseContact } from '@/shared/models/enterprise-contact';

const AMAZON = {
  email: 'info@amazon.com',
  website: 'https://www.amazon.com',
  phone1: '+1-800-123-4567',
  phone2: '+1-800-987-6543',
};

const BEST_BUY = {
  email: 'info@bestbuy.com',
  website: 'https://www.bestbuy.com',
  phone1: '+1-800-123-4567',
  phone2: '+1-800-987-6543',
};

const ANAYA = {
  email: 'info@anaya.com',
  website: 'https://www.anaya.com',
  phone1: '+34-900-123-456',
  phone2: '+34-900-987-654',
};

type EnterpriseContactFixture = typeof AMAZON;

export class EnterpriseContactMother extends BaseMother {
  static amazon(
    this: void,
    overrides?: Partial<EnterpriseContact>,
  ): EnterpriseContact {
    return EnterpriseContactMother.create(AMAZON, overrides);
  }

  static bestBuy(
    this: void,
    overrides?: Partial<EnterpriseContact>,
  ): EnterpriseContact {
    return EnterpriseContactMother.create(BEST_BUY, overrides);
  }

  static anaya(
    this: void,
    overrides?: Partial<EnterpriseContact>,
  ): EnterpriseContact {
    return EnterpriseContactMother.create(ANAYA, overrides);
  }

  protected static create(
    values: EnterpriseContactFixture,
    overrides?: Partial<EnterpriseContact>,
  ): EnterpriseContact {
    const fields = EnterpriseContactMother.merge<
      EnterpriseContactFixture,
      EnterpriseContact
    >(values, overrides);

    return EnterpriseContact.from(fields);
  }
}
