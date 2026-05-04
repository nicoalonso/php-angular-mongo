import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { PurchaseInvoice } from '@/purchase/model/purchase-invoice';

const INVOICE_1 = {
  number: 'INV-001',
  amount: 100.0,
  taxes: 20.0,
  total: 120.0,
};

const INVOICE_2 = {
  number: 'INV-002',
  amount: 135.0,
  taxes: 45.0,
  total: 180.0,
};

type PurchaseInvoiceFixture = typeof INVOICE_1;

export class PurchaseInvoiceMother extends BaseMother {
  static invoice1(
    this: void,
    overrides?: Partial<PurchaseInvoice>,
  ): PurchaseInvoice {
    return PurchaseInvoiceMother.create(INVOICE_1, overrides);
  }

  static invoice2(
    this: void,
    overrides?: Partial<PurchaseInvoice>,
  ): PurchaseInvoice {
    return PurchaseInvoiceMother.create(INVOICE_2, overrides);
  }

  protected static create(
    values: PurchaseInvoiceFixture,
    overrides?: Partial<PurchaseInvoice>,
  ): PurchaseInvoice {
    const fields = PurchaseInvoiceMother.merge<
      PurchaseInvoiceFixture,
      PurchaseInvoice
    >(values, overrides);

    return PurchaseInvoice.from(fields);
  }
}
