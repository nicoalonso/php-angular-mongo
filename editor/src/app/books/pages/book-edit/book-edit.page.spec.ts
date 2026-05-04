import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { AuthorService } from '@/authors/services/author.service';
import { EditorialService } from '@/editorials/services/editorial.service';
import { ToastService } from '@/shared/services/toast.service';
import { BookService } from '@/books/services/book-service';
import BookEditPage from '@/books/pages/book-edit/book-edit.page';
import { findById, findOneByAttribute } from '@tests/helpers/search-dom';
import { fireEvent } from '@testing-library/dom';

describe('BookEditPage', () => {
  let bookService: BookServiceStub;
  let authorService: AuthorServiceStub;
  let editorialService: EditorialServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: BookEditPage;
  let fixture: ComponentFixture<BookEditPage>;

  beforeEach(async () => {
    bookService = new BookServiceStub();
    authorService = new AuthorServiceStub();
    editorialService = new EditorialServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [BookEditPage],
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

    fixture = TestBed.createComponent(BookEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should author dialog when click on button', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
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
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-autor');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.authorSelectorVisible()).toBeTruthy();
  });

  it('should reset author when keydown on the author input', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    component.form.patchValue({ author: { id: 2, name: 'Author 2' } });
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
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
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
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
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
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'book-editorial');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.editorialSelectorVisible()).toBeTruthy();
  });

  it('should reset editorial when keydown on the editorial input', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    component.form.patchValue({ editorial: { id: 2, name: 'Editorial 2' } });
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
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    const editorial = editorialService.get(Ref.EditorialAnaya);
    component['onEditorialSelected'](editorial);
    fixture.detectChanges();

    expect(component.form.get('editorial')?.value).toEqual({
      id: editorial.id,
      name: editorial.name,
    });
  });

  it('should run when persist is called and form is valid', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    component.onSave();
    fixture.detectChanges();

    expect(bookService.updatePayload).not.toBeNull();
  });
});
