import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { SaleMother } from '@tests/fixtures/mothers/sale.mother';
import { Ref } from '@tests/fixtures/ref';
import { Sale } from '@/sales/model/sale';

export class SaleServiceStub extends EntityServiceStub<Sale> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const johnDoeSale1 = SaleMother.johnDoeSale1();
    this.addFixture(Ref.SaleJohnDoe1, johnDoeSale1);

    const johnDoeSale2 = SaleMother.johnDoeSale2();
    this.addFixture(Ref.SaleJohnDoe2, johnDoeSale2);
  }
}
