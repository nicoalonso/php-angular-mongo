import { provideHttpClient } from '@angular/common/http';
import {
  HttpTestingController,
  provideHttpClientTesting,
} from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';
import { buildEndpoint } from '@tests/helpers/http-util';
import { EditorialMother } from '@tests/fixtures/mothers/editorial.mother';
import { ApiResponse } from '@/shared/interfaces/api-response';
import { Editorial } from '@/editorials/model/editorial';
import { EditorialService } from '@/editorials/services/editorial.service';

describe('EditorialService', () => {
  let service: EditorialService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [provideHttpClient(), provideHttpClientTesting()],
    });
    service = TestBed.inject(EditorialService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should run when get item', (done) => {
    const editorial = EditorialMother.anaya();
    const mockResponse = { data: editorial } as ApiResponse<Editorial>;

    service.getItem(editorial.id).subscribe((result) => {
      expect(result.name).toEqual(editorial.name);
      done();
    });

    const url = buildEndpoint('editorials', editorial.id);
    const req = httpMock.expectOne(url);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse);
  });
});
