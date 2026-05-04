import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { SaleServiceStub } from '@tests/doubles/services/sale-service.stub';
import { saleResolver } from '@/sales/resolvers/sale.resolver';
import { SaleService } from '@/sales/services/sale.service';
import { Sale } from '@/sales/model/sale';

describe('saleResolver', () => {
  let saleService: SaleServiceStub;

  const executeResolver: ResolveFn<Sale> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() => saleResolver(...resolverParameters));

  beforeEach(() => {
    saleService = new SaleServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: SaleService, useValue: saleService }],
    });
  });

  it('should null when sale not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((sale) => {
        expect(sale).toBeNull();
        done();
      });
    }
  });

  it('should run when sale found', (done) => {
    const sale = saleService.put(Ref.SaleJohnDoe1);

    const route = {
      paramMap: new Map([['id', sale.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((sale) => {
        expect(sale).not.toBeNull();
        done();
      });
    }
  });
});
