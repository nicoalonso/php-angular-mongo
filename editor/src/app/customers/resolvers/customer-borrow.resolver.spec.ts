import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { Borrow } from '@/borrows/model/borrow';
import { BorrowService } from '@/borrows/services/borrow.service';
import { customerBorrowResolver } from '@/customers/resolvers/customer-borrow.resolver';

describe('customerBorrowResolver', () => {
  let borrowService: BorrowServiceStub;

  const executeResolver: ResolveFn<Borrow[]> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      customerBorrowResolver(...resolverParameters),
    );

  beforeEach(() => {
    borrowService = new BorrowServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: BorrowService, useValue: borrowService }],
    });
  });

  it('should empty when customer not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((borrows) => {
        expect(borrows).toHaveLength(0);
        done();
      });
    }
  });

  it('should not empty when customer found', (done) => {
    borrowService.attachAll();

    const route = {
      paramMap: new Map([['id', '12546']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((borrows) => {
        expect(borrows).toHaveLength(1);
        done();
      });
    }
  });
});
