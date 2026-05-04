import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { BookDescriptorMother } from '@tests/fixtures/mothers/book.mother';
import { SaleLine } from '@/sales/model/sale-line';

const JOHN_SALE_1_LINE_1 = {
  lineId: '5f5f67d3-4b16-40e9-98a7-4de6b58e6648',
  book: BookDescriptorMother.donQuixote,
  quantity: 2,
  price: 10.0,
  discount: 0.0,
  total: 20.0,
};

const JOHN_SALE_1_LINE_2 = {
  lineId: '10bc7d35-9f1d-4427-9c74-4b8cdc733ad3',
  book: BookDescriptorMother.romeoAndJuliet,
  quantity: 1,
  price: 12.0,
  discount: 0.0,
  total: 12.0,
};

const JOHN_SALE_2_LINE_1 = {
  lineId: 'ffc77796-c054-4c86-bb75-f208827b9c48',
  book: BookDescriptorMother.romeoAndJuliet,
  quantity: 3,
  price: 11.0,
  discount: 5.0,
  total: 31.35,
};

type SaleLineFixture = typeof JOHN_SALE_1_LINE_1;

export class SaleLineMother extends BaseMother {
  static johnSale1Line1(this: void, overrides?: Partial<SaleLine>): SaleLine {
    return SaleLineMother.create(JOHN_SALE_1_LINE_1, overrides);
  }

  static johnSale1Line2(this: void, overrides?: Partial<SaleLine>): SaleLine {
    return SaleLineMother.create(JOHN_SALE_1_LINE_2, overrides);
  }

  static johnSale2Line1(this: void, overrides?: Partial<SaleLine>): SaleLine {
    return SaleLineMother.create(JOHN_SALE_2_LINE_1, overrides);
  }

  protected static create(
    values: SaleLineFixture,
    overrides?: Partial<SaleLine>,
  ): SaleLine {
    const fields = SaleLineMother.merge<SaleLineFixture, SaleLine>(
      values,
      overrides,
    );

    return SaleLine.from(fields);
  }
}
