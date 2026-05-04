import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Purchase } from '@/purchase/model/purchase';
import { PurchaseMother } from '@tests/fixtures/mothers/purchase.mother';
import { Ref } from '@tests/fixtures/ref';

export class PurchaseServiceStub extends EntityServiceStub<Purchase> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const amazonInv1 = PurchaseMother.amazonInv1();
    this.addFixture(Ref.PurchaseAmazonInv1, amazonInv1);

    const bestBuyInv2 = PurchaseMother.bestBuyInv2();
    this.addFixture(Ref.PurchaseBestBuyInv2, bestBuyInv2);
  }
}
