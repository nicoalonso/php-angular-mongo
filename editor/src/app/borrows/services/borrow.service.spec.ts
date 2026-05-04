import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import {
  buildEndpoint,
  buildEndpointQueryString,
} from '@tests/helpers/http-util';
import { BorrowMother } from '@tests/fixtures/mothers/borrow.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Borrow } from '@/borrows/model/borrow';
import { BorrowCheckinPayload } from '@/borrows/model/borrow-checkin-payload';
import { BorrowService } from '@/borrows/services/borrow.service';
import { ListResult } from '@/shared/models/list-result';

describe('BorrowService', () => {
  let service: BorrowService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(BorrowService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const borrow = BorrowMother.johnDoe();
    const mockResponse = { data: borrow } as ApiResponse<Borrow>;

    service.getItem(borrow.id).subscribe((result) => {
      expect(result.borrowDate).toEqual(borrow.borrowDate);
      done();
    });

    const url = buildEndpoint('borrows', borrow.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should run when check in', (done) => {
    const borrow = BorrowMother.johnDoe();
    const line = borrow.lines[0];
    const payload = {
      lines: [{ lineId: line.lineId, bookId: line.book.id, returned: true }],
    } as BorrowCheckinPayload;

    service.checkIn(borrow.id, payload).subscribe((result) => {
      expect(result).toBeNull();
      done();
    });

    const url = buildEndpoint('borrows', borrow.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('PATCH');
    req.flush(null);
  });

  it('should run when obtain active by customer', (done) => {
    service.obtainActiveByCustomer('customer-id').subscribe((result) => {
      expect(result).toHaveLength(1);
      done();
    });

    const borrow = BorrowMother.johnDoe();
    const mockResponse = {
      items: [borrow],
    } as ListResult<Borrow>;

    const url = buildEndpointQueryString('borrows', {
      customerId: 'customer-id',
      returned: false,
    });
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });

  it('should run when manual penalty', (done) => {
    service.manualPenalty().subscribe((result) => {
      expect(result).toBeNull();
      done();
    });

    const url = buildEndpoint('borrows', 'manual-penalty');
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('POST');
    req.flush(null);
  });
});
