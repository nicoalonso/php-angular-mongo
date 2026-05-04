import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { Author } from '@/authors/model/author';

const SHAKESPEARE = {
  id: '22855dcc-4d6e-4cb4-bcd7-22141715bbfb',
  name: 'William Shakespeare',
  realName: 'William Shakespeare',
  genres: 'Tragedy, Comedy, History',
  biography: 'William Shakespeare was an English playwright, poet, and actor.',
  nationality: 'English',
  birthDate: ['1564-04-23', MotherMapping.DATE],
  deathDate: ['1616-04-23', MotherMapping.DATE],
  photoUrl: 'https://example.com/shakespeare.jpg',
  website: 'https://en.wikipedia.org/wiki/William_Shakespeare',
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

const CERVANTES = {
  id: '56e20174-11e9-4fbd-bded-7a3f6a179c43',
  name: 'Miguel de Cervantes',
  realName: 'Miguel de Cervantes Saavedra',
  genres: 'Novel, Drama, Poetry',
  biography:
    'Miguel de Cervantes was a Spanish writer widely regarded as one of the greatest writers in the Spanish language.',
  nationality: 'Spanish',
  birthDate: ['1547-09-29', MotherMapping.DATE],
  deathDate: ['1616-04-22', MotherMapping.DATE],
  photoUrl: 'https://example.com/cervantes.jpg',
  website: 'https://en.wikipedia.org/wiki/Miguel_de_Cervantes',
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type AuthorFixture = typeof SHAKESPEARE;

export class AuthorMother extends BaseMother {
  static shakespeare(this: void, overrides?: Partial<Author>): Author {
    return AuthorMother.create(SHAKESPEARE, overrides);
  }

  static cervantes(this: void, overrides?: Partial<Author>): Author {
    return AuthorMother.create(CERVANTES, overrides);
  }

  protected static create(
    values: AuthorFixture,
    overrides?: Partial<Author>,
  ): Author {
    const fields: Author = AuthorMother.merge<AuthorFixture, Author>(
      values,
      overrides,
    );

    return Author.from(fields);
  }
}
