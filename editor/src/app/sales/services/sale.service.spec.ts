import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { buildEndpoint } from '@tests/helpers/http-util';
import { SaleMother } from '@tests/fixtures/mothers/sale.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Sale } from '@/sales/model/sale';
import { SaleService } from '@/sales/services/sale.service';

describe('SaleService', () => {
  let service: SaleService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(SaleService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const sale = SaleMother.johnDoeSale1();
    const mockResponse = { data: sale } as ApiResponse<Sale>;

    service.getItem(sale.id).subscribe((result) => {
      expect(result.number).toEqual(sale.number);
      done();
    });

    const url = buildEndpoint('sales', sale.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });
});
