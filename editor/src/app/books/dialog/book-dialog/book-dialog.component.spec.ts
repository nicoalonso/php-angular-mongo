import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { BookService } from '@/books/services/book-service';
import { BookDialogComponent } from '@/books/dialog/book-dialog/book-dialog.component';
import { Ref } from '@tests/fixtures/ref';

describe('BookDialogComponent', () => {
  let bookService: BookServiceStub;
  let component: BookDialogComponent;
  let fixture: ComponentFixture<BookDialogComponent>;

  beforeEach(async () => {
    bookService = new BookServiceStub();

    await TestBed.configureTestingModule({
      imports: [BookDialogComponent],
      providers: [
        provideAnimationsAsync(),
        { provide: BookService, useValue: bookService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(BookDialogComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    bookService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add filters', () => {
    bookService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.componentRef.setInput('saleable', true);
    component.form.patchValue({ title: 'test' });
    fixture.detectChanges();

    expect(bookService.query).toEqual({ title: 'test', saleable: true });
  });

  it('should run when select a book', (done) => {
    const book = bookService.attach(Ref.BookDonQuixote);
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    component.selected.subscribe((value) => {
      expect(value).toEqual(book);
      done();
    });

    const event = new MouseEvent('click');
    component.onClickItem(book, event);

    expect(component.visible()).toBeFalsy();
  });
});
