import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { BookDescriptorMother } from '@tests/fixtures/mothers/book.mother';
import { PurchaseLine } from '@/purchase/model/purchase-line';

const AMAZON_LINE_1 = {
  lineId: 'd6579be4-4cc2-4346-9087-75c1b948c727',
  book: BookDescriptorMother.romeoAndJuliet,
  quantity: 2,
  unitPrice: 10.0,
  discountPercentage: 5.0,
  total: 19.0,
};

const AMAZON_LINE_2 = {
  lineId: '0176251c-b333-487e-9723-f43c95f8bb3b',
  book: BookDescriptorMother.donQuixote,
  quantity: 3,
  unitPrice: 15.0,
  discountPercentage: 10.0,
  total: 40.5,
};

const BEST_BUY_LINE_1 = {
  lineId: '52bea48d-6f57-42d8-9d1d-ec23c244aed0',
  book: BookDescriptorMother.romeoAndJuliet,
  quantity: 1,
  unitPrice: 20.0,
  discountPercentage: 0.0,
  total: 20.0,
};

type PurchaseLineFixture = typeof AMAZON_LINE_1;

export class PurchaseLineMother extends BaseMother {
  static amazonLine1(
    this: void,
    overrides?: Partial<PurchaseLine>,
  ): PurchaseLine {
    return PurchaseLineMother.create(AMAZON_LINE_1, overrides);
  }

  static amazonLine2(
    this: void,
    overrides?: Partial<PurchaseLine>,
  ): PurchaseLine {
    return PurchaseLineMother.create(AMAZON_LINE_2, overrides);
  }

  static bestBuyLine1(
    this: void,
    overrides?: Partial<PurchaseLine>,
  ): PurchaseLine {
    return PurchaseLineMother.create(BEST_BUY_LINE_1, overrides);
  }

  protected static create(
    values: PurchaseLineFixture,
    overrides?: Partial<PurchaseLine>,
  ): PurchaseLine {
    const fields = PurchaseLineMother.merge<PurchaseLineFixture, PurchaseLine>(
      values,
      overrides,
    );

    return PurchaseLine.from(fields);
  }
}
