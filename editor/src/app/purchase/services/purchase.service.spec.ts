import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { buildEndpoint } from '@tests/helpers/http-util';
import { PurchaseMother } from '@tests/fixtures/mothers/purchase.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Purchase } from '@/purchase/model/purchase';
import { PurchaseService } from '@/purchase/services/purchase.service';

describe('PurchaseService', () => {
  let service: PurchaseService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(PurchaseService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const purchase = PurchaseMother.amazonInv1();
    const mockResponse = { data: purchase } as ApiResponse<Purchase>;

    service.getItem(purchase.id).subscribe((result) => {
      expect(result.invoice.number).toEqual(purchase.invoice.number);
      done();
    });

    const url = buildEndpoint('purchases', purchase.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });
});
