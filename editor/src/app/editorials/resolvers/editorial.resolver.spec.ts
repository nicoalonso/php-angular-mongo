import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { Editorial } from '@/editorials/model/editorial';
import { editorialResolver } from '@/editorials/resolvers/editorial.resolver';
import { EditorialService } from '@/editorials/services/editorial.service';

describe('editorialResolver', () => {
  let editorialService: EditorialServiceStub;

  const executeResolver: ResolveFn<Editorial> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      editorialResolver(...resolverParameters),
    );

  beforeEach(() => {
    editorialService = new EditorialServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: EditorialService, useValue: editorialService }],
    });
  });

  it('should null when editorial not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((editorial) => {
        expect(editorial).toBeNull();
        done();
      });
    }
  });

  it('should run when editorial found', (done) => {
    const editorial = editorialService.put(Ref.EditorialAnaya);

    const route = {
      paramMap: new Map([['id', editorial.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((editorial) => {
        expect(editorial).not.toBeNull();
        done();
      });
    }
  });
});
