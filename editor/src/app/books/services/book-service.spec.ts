import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { buildEndpoint } from '@tests/helpers/http-util';
import { BookMother } from '@tests/fixtures/mothers/book.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Book } from '@/books/model/book';
import { BookService } from '@/books/services/book-service';

describe('BookService', () => {
  let service: BookService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(BookService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const book = BookMother.donQuixote();
    const mockResponse = { data: book } as ApiResponse<Book>;

    service.getItem(book.id).subscribe((result) => {
      expect(result.title).toEqual(book.title);
      done();
    });

    const url = buildEndpoint('books', book.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should check availability', (done) => {
    const book = BookMother.romeoAndJuliet();
    const mockResponse = { data: { available: true } } as ApiResponse<{
      available: boolean;
    }>;

    service.checkAvailability(book.id, true).subscribe((available) => {
      expect(available).toBe(true);
      done();
    });

    const url = buildEndpoint('books', book.id) + '/available?sale=true';
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should false when bad request on availability', (done) => {
    const book = BookMother.romeoAndJuliet();

    service.checkAvailability(book.id, true).subscribe((available) => {
      expect(available).toBeFalsy();
      done();
    });

    const url = buildEndpoint('books', book.id) + '/available?sale=true';
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush('Bad Request', { status: 400, statusText: 'Bad Request' });
  });
});
