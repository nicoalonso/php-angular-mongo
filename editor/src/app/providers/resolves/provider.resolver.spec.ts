import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { Editorial } from '@/editorials/model/editorial';
import { providerResolver } from '@/providers/resolves/provider.resolver';
import { ProviderService } from '@/providers/services/provider.service';

describe('providerResolver', () => {
  let providerService: ProviderServiceStub;

  const executeResolver: ResolveFn<Editorial> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      providerResolver(...resolverParameters),
    );

  beforeEach(() => {
    providerService = new ProviderServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: ProviderService, useValue: providerService }],
    });
  });

  it('should null when provider not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((provider) => {
        expect(provider).toBeNull();
        done();
      });
    }
  });

  it('should run when provider found', (done) => {
    const provider = providerService.put(Ref.ProviderAmazon);

    const route = {
      paramMap: new Map([['id', provider.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((provider) => {
        expect(provider).not.toBeNull();
        done();
      });
    }
  });
});
