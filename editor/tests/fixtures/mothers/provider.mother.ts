import { EnterpriseContactMother } from '@tests/fixtures/mothers/enterprise-contact.mother';
import { AddressMother } from '@tests/fixtures/mothers/address.mother';
import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { Provider } from '@/providers/model/provider';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';

const AMAZON = {
  id: 'b684646e-79cd-410f-a601-08c2e49e80ed',
  name: 'Amazon',
  comercialName: 'Amazon, Inc.',
  contact: EnterpriseContactMother.amazon,
  address: AddressMother.anytown,
  vatNumber: 'B36565656',
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

const BEST_BUY = {
  id: '68228309-8323-4122-9557-fe7af05ce846',
  name: 'Best Buy',
  comercialName: 'Best Buy Co., Inc.',
  contact: EnterpriseContactMother.bestBuy,
  address: AddressMother.anytown,
  vatNumber: 'B36565656',
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

export type ProviderFixture = typeof AMAZON;

export class ProviderMother extends BaseMother {
  static amazon(this: void, overrides?: Partial<Provider>): Provider {
    return ProviderMother.create(AMAZON, overrides);
  }

  static bestBuy(this: void, overrides?: Partial<Provider>): Provider {
    return ProviderMother.create(BEST_BUY, overrides);
  }

  protected static create(
    values: ProviderFixture,
    overrides?: Partial<Provider>,
  ): Provider {
    const fields = ProviderMother.merge<ProviderFixture, Provider>(
      values,
      overrides,
    );

    return Provider.from(fields);
  }
}
