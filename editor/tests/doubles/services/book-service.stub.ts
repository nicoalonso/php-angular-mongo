import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Book } from '@/books/model/book';
import { BookMother } from '@tests/fixtures/mothers/book.mother';
import { Ref } from '@tests/fixtures/ref';
import { Observable, of } from 'rxjs';

export class BookServiceStub extends EntityServiceStub<Book> {
  public available: boolean = true;

  constructor() {
    super();
  }

  checkAvailability(
    _bookId: string,
    _isSale: boolean = false,
  ): Observable<boolean> {
    return of(this.available);
  }

  protected makeFixtures(): void {
    const romeoAndJuliet = BookMother.romeoAndJuliet();
    this.addFixture(Ref.BookRomeoAndJuliet, romeoAndJuliet);

    const donQuixote = BookMother.donQuixote();
    this.addFixture(Ref.BookDonQuixote, donQuixote);
  }
}
