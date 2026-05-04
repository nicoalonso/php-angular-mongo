import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ActivatedRoute, provideRouter, Router } from '@angular/router';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { BehaviorSubject } from 'rxjs';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { findOneByContent } from '@tests/helpers/search-dom';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { AuthorService } from '@/authors/services/author.service';
import AuthorListPage from '@/authors/pages/author-list/author-list.page';

describe('AuthorListPage', () => {
  let authorService: AuthorServiceStub;
  let component: AuthorListPage;
  let fixture: ComponentFixture<AuthorListPage>;
  let queryParams: BehaviorSubject<Record<string, string>>;

  beforeEach(async () => {
    authorService = new AuthorServiceStub();
    queryParams = new BehaviorSubject({});

    await TestBed.configureTestingModule({
      imports: [AuthorListPage],
      providers: [
        provideRouter([]),
        { provide: AuthorService, useValue: authorService },
        {
          provide: ActivatedRoute,
          useValue: { queryParams },
        },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(AuthorListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    authorService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    authorService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nuevo Autor',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['authors', 'create']);
  });

  it('should click on table row', () => {
    const authors = authorService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const cell = findOneByContent<HTMLTableCellElement>(
      fixture,
      'td',
      'William Shakespeare',
    );
    fireEvent.click(cell);

    expect(navigateSpy).toHaveBeenCalledWith(['authors', authors[0].id]);
  });

  it('should handle query params', () => {
    const author = authorService.attach(Ref.AuthorCervantes);
    queryParams.next({ id: author.id });

    fixture.detectChanges();

    expect(component.constraints()).toEqual([
      { field: 'id', value: author.id },
    ]);
  });
});
