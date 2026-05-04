import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { BorrowService } from '@/borrows/services/borrow.service';
import { Borrow } from '@/borrows/model/borrow';
import { borrowResolver } from '@/borrows/resolvers/borrow.resolver';

describe('borrowResolver', () => {
  let borrowService: BorrowServiceStub;

  const executeResolver: ResolveFn<Borrow> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() => borrowResolver(...resolverParameters));

  beforeEach(() => {
    borrowService = new BorrowServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: BorrowService, useValue: borrowService }],
    });
  });

  it('should null when borrow not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((borrow) => {
        expect(borrow).toBeNull();
        done();
      });
    }
  });

  it('should run when borrow found', (done) => {
    const borrow = borrowService.put(Ref.BorrowJohnDoe);

    const route = {
      paramMap: new Map([['id', borrow.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((borrow) => {
        expect(borrow).not.toBeNull();
        done();
      });
    }
  });
});
