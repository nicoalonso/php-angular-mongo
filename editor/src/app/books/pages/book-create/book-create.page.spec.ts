import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { FixturePayload } from '@tests/fixtures/fixture-payload';
import { findById, findOneByAttribute } from '@tests/helpers/search-dom';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { AuthorService } from '@/authors/services/author.service';
import { EditorialService } from '@/editorials/services/editorial.service';
import { BookService } from '@/books/services/book-service';
import { Book } from '@/books/model/book';
import BookCreatePage from '@/books/pages/book-create/book-create.page';

describe('BookCreatePage', () => {
  let bookService: BookServiceStub;
  let authorService: AuthorServiceStub;
  let editorialService: EditorialServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: BookCreatePage;
  let fixture: ComponentFixture<BookCreatePage>;

  beforeEach(async () => {
    bookService = new BookServiceStub();
    authorService = new AuthorServiceStub();
    editorialService = new EditorialServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [BookCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: BookService, useValue: bookService },
        { provide: AuthorService, useValue: authorService },
        { provide: EditorialService, useValue: editorialService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BookCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should author dialog when click on button', () => {
    fixture.detectChanges();

    const button = findOneByAttribute<HTMLButtonElement>(
      fixture,
      'p-button',
      'ptooltip',
      'Seleccionar autor',
    );

    fireEvent.click(button);
    fixture.detectChanges();

    expect(component.authorSelectorVisible()).toBeTruthy();
  });

  it('should author dialog when keydown on the author input', () => {
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-autor');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.authorSelectorVisible()).toBeTruthy();
  });

  it('should reset author when keydown on the author input', () => {
    component.form.patchValue({ author: { id: 1, name: 'Author 1' } });
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-autor');

    fireEvent.keyDown(input, { key: 'Backspace' });
    fixture.detectChanges();

    expect(component.form.get('author')?.value).toEqual({
      id: null,
      name: null,
    });
  });

  it('should run when user selected an author', () => {
    fixture.detectChanges();

    const author = authorService.get(Ref.AuthorShakespeare);
    component['onAuthorSelected'](author);
    fixture.detectChanges();

    expect(component.form.get('author')?.value).toEqual({
      id: author.id,
      name: author.name,
    });
  });

  it('should editorial dialog when click on button', () => {
    fixture.detectChanges();

    const button = findOneByAttribute<HTMLButtonElement>(
      fixture,
      'p-button',
      'ptooltip',
      'Seleccionar editorial',
    );

    fireEvent.click(button);
    fixture.detectChanges();

    expect(component.editorialSelectorVisible()).toBeTruthy();
  });

  it('should editorial dialog when keydown on the editorial input', () => {
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-editorial');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.editorialSelectorVisible()).toBeTruthy();
  });

  it('should reset editorial when keydown on the editorial input', () => {
    component.form.patchValue({ editorial: { id: 1, name: 'Editorial 1' } });
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-editorial');

    fireEvent.keyDown(input, { key: 'Backspace' });
    fixture.detectChanges();

    expect(component.form.get('editorial')?.value).toEqual({
      id: null,
      name: null,
    });
  });

  it('should run when user selected an editorial', () => {
    fixture.detectChanges();

    const editorial = editorialService.get(Ref.EditorialAnaya);
    component['onEditorialSelected'](editorial);
    fixture.detectChanges();

    expect(component.form.get('editorial')?.value).toEqual({
      id: editorial.id,
      name: editorial.name,
    });
  });

  it('should run when go to second step and form is valid', () => {
    const payload = new FixturePayload<Book>();
    const data = payload.load('book-create');
    data.detail.publishedAt = new Date(data.detail.publishedAt);
    component.form.patchValue(data);

    component.onNextStep();
    component.onNextStep();
    fixture.detectChanges();

    expect(component.activeStep()).toBe(2);
  });

  it('should run when create', () => {
    const payload = new FixturePayload<Book>();
    const data = payload.load('book-create');
    component.form.patchValue(data);
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    component.onCreate();

    expect(bookService.createPayload).not.toBeNull();
    const createPayload = bookService.createPayload! as Book;
    expect(createPayload.title).toBe(data.title);
    expect(navigateSpy).toHaveBeenCalledWith(['/books']);
  });
});
