import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { authorResolver } from '@/authors/resolvers/author.resolver';
import { Author } from '@/authors/model/author';
import { AuthorService } from '@/authors/services/author.service';

describe('authorResolver', () => {
  let authorService: AuthorServiceStub;

  const executeResolver: ResolveFn<Author> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() => authorResolver(...resolverParameters));

  beforeEach(() => {
    authorService = new AuthorServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: AuthorService, useValue: authorService }],
    });
  });

  it('should null when author not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((author) => {
        expect(author).toBeNull();
        done();
      });
    }
  });

  it('should run when author found', (done) => {
    const author = authorService.put(Ref.AuthorCervantes);

    const route = {
      paramMap: new Map([['id', author.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((author) => {
        expect(author).not.toBeNull();
        done();
      });
    }
  });
});
