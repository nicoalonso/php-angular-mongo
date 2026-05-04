import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { ProviderMother } from '@tests/fixtures/mothers/provider.mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { PurchaseInvoiceMother } from '@tests/fixtures/mothers/purchase-invoice.mother';
import { Purchase } from '@/purchase/model/purchase';
import { PurchaseLineMother } from '@tests/fixtures/mothers/purchase-line.mother';

const AMAZON_INV_1 = {
  id: '0b7d5511-e773-4157-81a4-8097c33c68a4',
  provider: ProviderMother.amazon,
  purchasedAt: ['2026-03-02', MotherMapping.DATE],
  invoice: PurchaseInvoiceMother.invoice1,
  lines: [
    MotherMapping.ARRAY,
    [PurchaseLineMother.amazonLine1, PurchaseLineMother.amazonLine2],
  ],
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

const BEST_BUY_INV_2 = {
  id: '9b5bd948-b280-4b60-a46a-ed5d0a0f799d',
  provider: ProviderMother.bestBuy,
  purchasedAt: ['2026-03-02', MotherMapping.DATE],
  invoice: PurchaseInvoiceMother.invoice2,
  lines: [MotherMapping.ARRAY, [PurchaseLineMother.bestBuyLine1]],
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type PurchaseFixture = typeof AMAZON_INV_1;

export class PurchaseMother extends BaseMother {
  static amazonInv1(this: void, overrides?: Partial<Purchase>): Purchase {
    return PurchaseMother.create(AMAZON_INV_1, overrides);
  }

  static bestBuyInv2(this: void, overrides?: Partial<Purchase>): Purchase {
    return PurchaseMother.create(BEST_BUY_INV_2, overrides);
  }

  protected static create(
    values: PurchaseFixture,
    overrides?: Partial<Purchase>,
  ): Purchase {
    const fields = PurchaseMother.merge<PurchaseFixture, Purchase>(
      values,
      overrides,
    );

    return Purchase.from(fields);
  }
}
