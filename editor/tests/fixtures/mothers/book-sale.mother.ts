import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { BookSale } from '@/books/model/book-sale';

const VALID = {
  saleable: true,
  price: 100.0,
  discount: 10.0,
};

type BookSaleFixture = typeof VALID;

export class BookSaleMother extends BaseMother {
  static valid(this: void, overrides?: Partial<BookSale>): BookSale {
    return BookSaleMother.create(VALID, overrides);
  }

  protected static create(
    values: BookSaleFixture,
    overrides?: Partial<BookSale>,
  ): BookSale {
    const fields: BookSale = BookSaleMother.merge<BookSaleFixture, BookSale>(
      values,
      overrides,
    );

    return BookSale.from(fields);
  }
}
