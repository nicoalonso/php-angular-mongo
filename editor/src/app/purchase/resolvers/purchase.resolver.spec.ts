import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { PurchaseServiceStub } from '@tests/doubles/services/purchase-service.stub';
import { Purchase } from '@/purchase/model/purchase';
import { purchaseResolver } from '@/purchase/resolvers/purchase.resolver';
import { PurchaseService } from '@/purchase/services/purchase.service';

describe('purchaseResolver', () => {
  let purchaseService: PurchaseServiceStub;

  const executeResolver: ResolveFn<Purchase> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      purchaseResolver(...resolverParameters),
    );

  beforeEach(() => {
    purchaseService = new PurchaseServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: PurchaseService, useValue: purchaseService }],
    });
  });

  it('should null when purchase not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((purchase) => {
        expect(purchase).toBeNull();
        done();
      });
    }
  });

  it('should run when purchase found', (done) => {
    const purchase = purchaseService.put(Ref.PurchaseAmazonInv1);

    const route = {
      paramMap: new Map([['id', purchase.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((purchase) => {
        expect(purchase).not.toBeNull();
        done();
      });
    }
  });
});
