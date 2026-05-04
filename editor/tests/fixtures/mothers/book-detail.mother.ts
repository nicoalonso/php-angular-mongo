import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { BookDetail } from '@/books/model/book-detail';

const VALID = {
  edition: '001',
  isbn: '978-1234567890',
  language: 'English',
  publishedAt: ['2020-01-01', MotherMapping.DATE],
  pages: 100,
};

const QUIJOTE = {
  edition: '001',
  isbn: '978-1234567890',
  language: 'Spanish',
  publishedAt: ['1615-01-01', MotherMapping.DATE],
  pages: 100,
};

type BookDetailFixture = typeof VALID;

export class BookDetailMother extends BaseMother {
  static valid(this: void, overrides?: Partial<BookDetail>): BookDetail {
    return BookDetailMother.create(VALID, overrides);
  }

  static quijote(this: void, overrides?: Partial<BookDetail>): BookDetail {
    return BookDetailMother.create(QUIJOTE, overrides);
  }

  protected static create(
    values: BookDetailFixture,
    overrides?: Partial<BookDetail>,
  ): BookDetail {
    const fields: BookDetail = BookDetailMother.merge<
      BookDetailFixture,
      BookDetail
    >(values, overrides);

    return BookDetail.from(fields);
  }
}
