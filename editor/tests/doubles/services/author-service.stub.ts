import { Author } from '@/authors/model/author';
import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { AuthorMother } from '@tests/fixtures/mothers/author.mother';
import { Ref } from '@tests/fixtures/ref';

export class AuthorServiceStub extends EntityServiceStub<Author> {
  constructor() {
    super();
  }

  protected makeFixtures(): void {
    const shakespeare = AuthorMother.shakespeare();
    this.addFixture(Ref.AuthorShakespeare, shakespeare);

    const cervantes = AuthorMother.cervantes();
    this.addFixture(Ref.AuthorCervantes, cervantes);
  }
}
