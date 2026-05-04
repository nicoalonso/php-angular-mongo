import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter, Router } from '@angular/router';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { BookService } from '@/books/services/book-service';
import BookListPage from '@/books/pages/book-list/book-list.page';

describe('BookListPage', () => {
  let bookService: BookServiceStub;
  let component: BookListPage;
  let fixture: ComponentFixture<BookListPage>;

  beforeEach(async () => {
    bookService = new BookServiceStub();

    await TestBed.configureTestingModule({
      imports: [BookListPage],
      providers: [
        provideRouter([]),
        { provide: BookService, useValue: bookService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BookListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    bookService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    bookService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nuevo Libro',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['books', 'create']);
  });
});
