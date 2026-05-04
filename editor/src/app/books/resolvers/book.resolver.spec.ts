import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { bookResolver } from '@/books/resolvers/book.resolver';
import { Book } from '@/books/model/book';
import { BookService } from '@/books/services/book-service';

describe('bookResolver', () => {
  let bookService: BookServiceStub;

  const executeResolver: ResolveFn<Book> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() => bookResolver(...resolverParameters));

  beforeEach(() => {
    bookService = new BookServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: BookService, useValue: bookService }],
    });
  });

  it('should null when book not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((book) => {
        expect(book).toBeNull();
        done();
      });
    }
  });

  it('should run when book found', (done) => {
    const book = bookService.put(Ref.BookRomeoAndJuliet);

    const route = {
      paramMap: new Map([['id', book.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((book) => {
        expect(book).not.toBeNull();
        done();
      });
    }
  });
});
