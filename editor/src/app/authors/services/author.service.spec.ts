import { TestBed } from '@angular/core/testing';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { provideHttpClient } from '@angular/common/http';
import { AuthorMother } from '@tests/fixtures/mothers/author.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { AuthorService } from '@/authors/services/author.service';
import { Author } from '@/authors/model/author';
import {
  buildEndpoint,
  buildEndpointQueryString,
} from '@tests/helpers/http-util';
import { ListResult } from '@/shared/models/list-result';
import { ListQuery } from '@/shared/interfaces/list-query';

describe('AuthorService', () => {
  let service: AuthorService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(AuthorService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const author = AuthorMother.cervantes();
    const mockResponse = { data: author } as ApiResponse<Author>;

    service.getItem(author.id).subscribe((result) => {
      expect(result.name).toEqual(author.name);
      done();
    });

    const url = buildEndpoint('authors', author.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should run when search', (done) => {
    const author = AuthorMother.cervantes();
    const mockResponse = { items: [author] } as ListResult<Author>;

    const query = { name: 'Cervantes' } as ListQuery;

    service.search(query).subscribe((result) => {
      expect(result.items).toHaveLength(1);
      done();
    });

    const url = buildEndpointQueryString('authors', query);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should empty when search', (done) => {
    const mockResponse = { items: [] } as ListResult<never>;
    const query = { name: 'wrong' } as ListQuery;

    service.search(query).subscribe((result) => {
      expect(result.items).toHaveLength(0);
      done();
    });

    const url = buildEndpointQueryString('authors', query);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should empty when wrong response', (done) => {
    const query = { name: 'fail' } as ListQuery;

    service.search(query).subscribe((result) => {
      expect(result.items).toHaveLength(0);
      done();
    });

    const url = buildEndpointQueryString('authors', query);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush({});
  });

  it('should fail when bad request', (done) => {
    const query = { wrong: 'dummy' } as ListQuery;

    service.search(query).subscribe((result) => {
      expect(result.items).toHaveLength(0);
      done();
    });

    const url = buildEndpointQueryString('authors', query);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush('Bad request', { status: 400, statusText: 'Bad Request' });
  });

  it('should run when create item', (done) => {
    const author = AuthorMother.shakespeare();
    const mockResponse = { data: author } as ApiResponse<Author>;

    service.createItem(author).subscribe((result) => {
      expect(result.name).toEqual(author.name);
      done();
    });

    const url = buildEndpoint('authors');
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('POST');
    req.flush(mockResponse);
  });

  it('should run when update item', (done) => {
    const author = AuthorMother.shakespeare();

    service.updateItem(author.id, author).subscribe((result) => {
      expect(result).toBeNull();
      done();
    });

    const url = buildEndpoint('authors', author.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('PUT');
    req.flush(null);
  });

  it('should run when delete item', (done) => {
    const author = AuthorMother.shakespeare();

    service.removeItem(author).subscribe((result) => {
      expect(result).toBeNull();
      done();
    });

    const url = buildEndpoint('authors', author.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('DELETE');
    req.flush(null);
  });
});
