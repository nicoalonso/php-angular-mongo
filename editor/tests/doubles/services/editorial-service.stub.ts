import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Editorial } from '@/editorials/model/editorial';
import { EditorialMother } from '@tests/fixtures/mothers/editorial.mother';
import { Ref } from '@tests/fixtures/ref';

export class EditorialServiceStub extends EntityServiceStub<Editorial> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const anaya = EditorialMother.anaya();
    this.addFixture(Ref.EditorialAnaya, anaya);
  }
}
