import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { SaleInvoice } from '@/sales/model/sale-invoice';

const JOHN_DOE_SALE_1 = {
  date: ['2024-01-01', MotherMapping.DATE],
  amount: 100,
  taxPercentage: 21,
  taxes: 21,
  total: 121,
};

const JOHN_DOE_SALE_2 = {
  date: ['2026-03-06', MotherMapping.DATE],
  amount: 80,
  taxPercentage: 21,
  taxes: 16.8,
  total: 96.8,
};

type SaleInvoiceFixture = typeof JOHN_DOE_SALE_1;

export class SaleInvoiceMother extends BaseMother {
  static johnDoeSale1(
    this: void,
    overrides?: Partial<SaleInvoice>,
  ): SaleInvoice {
    return SaleInvoiceMother.create(JOHN_DOE_SALE_1, overrides);
  }

  static johnDoeSale2(
    this: void,
    overrides?: Partial<SaleInvoice>,
  ): SaleInvoice {
    return SaleInvoiceMother.create(JOHN_DOE_SALE_2, overrides);
  }

  protected static create(
    values: SaleInvoiceFixture,
    overrides?: Partial<SaleInvoice>,
  ): SaleInvoice {
    const fields = SaleInvoiceMother.merge<SaleInvoiceFixture, SaleInvoice>(
      values,
      overrides,
    );

    return SaleInvoice.from(fields);
  }
}
