import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConfirmationService } from 'primeng/api';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { findOneByContent } from '@tests/helpers/search-dom';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { ConfirmationServiceStub } from '@tests/doubles/framework/confirmation-service.stub';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { BookService } from '@/books/services/book-service';
import BookViewPage from '@/books/pages/book-view/book-view.page';

describe('BookViewPage', () => {
  let bookService: BookServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: BookViewPage;
  let fixture: ComponentFixture<BookViewPage>;

  beforeEach(async () => {
    bookService = new BookServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [BookViewPage],
      providers: [
        provideRouter([]),
        { provide: BookService, useValue: bookService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(BookViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BookViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const book = bookService.get(Ref.BookDonQuixote);
    book.createdAt = new Date();
    fixture.componentRef.setInput('book', book);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput('book', bookService.get(Ref.BookDonQuixote));
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');
    fixture.detectChanges();

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Eliminar',
    );
    fireEvent.click(button);

    expect(confirmationService.data).not.toBeNull();
    confirmationService.accept();

    expect(bookService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
