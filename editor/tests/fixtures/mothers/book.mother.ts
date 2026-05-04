import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { AuthorMother } from '@tests/fixtures/mothers/author.mother';
import { EditorialMother } from '@tests/fixtures/mothers/editorial.mother';
import { BookDetailMother } from '@tests/fixtures/mothers/book-detail.mother';
import { BookSaleMother } from '@tests/fixtures/mothers/book-sale.mother';
import { Book } from '@/books/model/book';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { BookDescriptor } from '@/books/model/book-descriptor';

const ROMEO_AND_JULIET = {
  id: '0e5b4225-063e-4330-a84b-09d175ed0270',
  title: 'Romeo and Juliet',
  description:
    'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
  author: AuthorMother.shakespeare,
  editorial: EditorialMother.anaya,
  detail: BookDetailMother.valid,
  sale: BookSaleMother.valid,
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

const DON_QUIXOTE = {
  id: 'b177f648-d843-4ada-b7c2-f0155ebf87d7',
  title: 'Don Quijote',
  description:
    'Don Quijote de la Mancha is a Spanish novel by Miguel de Cervantes. It follows the adventures of a nobleman who reads so many chivalric romances that he loses his sanity and decides to become a knight-errant, reviving chivalry and serving his nation.',
  author: AuthorMother.cervantes,
  editorial: EditorialMother.anaya,
  detail: BookDetailMother.quijote,
  sale: BookSaleMother.valid,
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type BookFixture = typeof ROMEO_AND_JULIET;

export class BookMother extends BaseMother {
  static romeoAndJuliet(this: void, overrides?: Partial<Book>): Book {
    return BookMother.create(ROMEO_AND_JULIET, overrides);
  }

  static donQuixote(this: void, overrides?: Partial<Book>): Book {
    return BookMother.create(DON_QUIXOTE, overrides);
  }

  protected static create(
    values: BookFixture,
    overrides?: Partial<Book>,
  ): Book {
    const fields: Book = BookMother.merge<BookFixture, Book>(values, overrides);
    return Book.from(fields);
  }
}

export class BookDescriptorMother {
  static romeoAndJuliet(this: void, overrides?: Partial<Book>): BookDescriptor {
    const book = BookMother.romeoAndJuliet(overrides);
    return new BookDescriptor(book.id, book.title, book.detail.isbn);
  }

  static donQuixote(this: void, overrides?: Partial<Book>): BookDescriptor {
    const book = BookMother.donQuixote(overrides);
    return new BookDescriptor(book.id, book.title, book.detail.isbn);
  }
}
