import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { buildEndpoint } from '@tests/helpers/http-util';
import { ProviderMother } from '@tests/fixtures/mothers/provider.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Provider } from '@/providers/model/provider';
import { ProviderService } from '@/providers/services/provider.service';

describe('ProviderService', () => {
  let service: ProviderService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(ProviderService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const provider = ProviderMother.amazon();
    const mockResponse = { data: provider } as ApiResponse<Provider>;

    service.getItem(provider.id).subscribe((result) => {
      expect(result.name).toEqual(provider.name);
      done();
    });

    const url = buildEndpoint('providers', provider.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });
});
