import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Provider } from '@/providers/model/provider';
import { ProviderMother } from '@tests/fixtures/mothers/provider.mother';
import { Ref } from '@tests/fixtures/ref';

export class ProviderServiceStub extends EntityServiceStub<Provider> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const amazon = ProviderMother.amazon();
    this.addFixture(Ref.ProviderAmazon, amazon);

    const bestBuy = ProviderMother.bestBuy();
    this.addFixture(Ref.ProviderBestBuy, bestBuy);
  }
}
