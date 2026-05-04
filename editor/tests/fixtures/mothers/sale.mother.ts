import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { CustomerDescriptorMother } from '@tests/fixtures/mothers/customer.mother';
import { SaleInvoiceMother } from '@tests/fixtures/mothers/sale-invoice.mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { Sale } from '@/sales/model/sale';
import { SaleLineMother } from '@tests/fixtures/mothers/sale-line.mother';

const JOHN_DOE_SALE_1 = {
  id: 'dd7f5e56-2cbc-411b-b1a7-7ee2d4196dd3',
  customer: CustomerDescriptorMother.johnDoe,
  number: 'F-00001',
  invoice: SaleInvoiceMother.johnDoeSale1,
  lines: [
    MotherMapping.ARRAY,
    [SaleLineMother.johnSale1Line1, SaleLineMother.johnSale1Line2],
  ],
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

const JOHN_DOE_SALE_2 = {
  id: '6f3d4992-aaab-494d-98a6-13a21c6f7e1c',
  customer: CustomerDescriptorMother.johnDoe,
  number: 'F-00001',
  invoice: SaleInvoiceMother.johnDoeSale2,
  lines: [MotherMapping.ARRAY, [SaleLineMother.johnSale2Line1]],
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type SaleFixture = typeof JOHN_DOE_SALE_1;

export class SaleMother extends BaseMother {
  static johnDoeSale1(this: void, overrides?: Partial<Sale>): Sale {
    return SaleMother.create(JOHN_DOE_SALE_1, overrides);
  }

  static johnDoeSale2(this: void, overrides?: Partial<Sale>): Sale {
    return SaleMother.create(JOHN_DOE_SALE_2, overrides);
  }

  protected static create(
    values: SaleFixture,
    overrides?: Partial<Sale>,
  ): Sale {
    const fields = SaleMother.merge<SaleFixture, Sale>(values, overrides);
    return Sale.from(fields);
  }
}
