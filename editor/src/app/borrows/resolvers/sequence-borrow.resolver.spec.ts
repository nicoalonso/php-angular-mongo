import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { SequenceServiceStub } from '@tests/doubles/services/sequence-service.stub';
import { sequenceBorrowResolver } from '@/borrows/resolvers/sequence-borrow.resolver';
import { SequenceService } from '@/shared/services/sequence.service';

describe('sequenceBorrowResolver', () => {
  let sequenceService: SequenceServiceStub;

  const executeResolver: ResolveFn<string> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      sequenceBorrowResolver(...resolverParameters),
    );

  beforeEach(() => {
    sequenceService = new SequenceServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: SequenceService, useValue: sequenceService }],
    });
  });

  it('should run when simulate', (done) => {
    const route = {} as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((number) => {
        expect(number).toBe('P-00001');
        done();
      });
    }
  });
});
