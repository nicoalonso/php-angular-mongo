import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { CustomerService } from '@/customers/services/customer.service';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { buildEndpoint } from '@tests/helpers/http-util';
import { CustomerMother } from '@tests/fixtures/mothers/customer.mother';
import { Customer } from '@/customers/model/customer';

describe('CustomerService', () => {
  let service: CustomerService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(CustomerService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const customer = CustomerMother.johnDoe();
    const mockResponse = { data: customer } as ApiResponse<Customer>;

    service.getItem(customer.id).subscribe((result) => {
      expect(result.name).toEqual(customer.name);
      done();
    });

    const url = buildEndpoint('customers', customer.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });
});
