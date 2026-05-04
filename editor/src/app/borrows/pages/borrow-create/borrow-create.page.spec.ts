import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { BookMother } from '@tests/fixtures/mothers/book.mother';
import { CustomerMother } from '@tests/fixtures/mothers/customer.mother';
import { findById } from '@tests/helpers/search-dom';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { BookService } from '@/books/services/book-service';
import { CustomerService } from '@/customers/services/customer.service';
import { BorrowService } from '@/borrows/services/borrow.service';
import BorrowCreatePage from '@/borrows/pages/borrow-create/borrow-create.page';

describe('BorrowCreatePage', () => {
  let borrowService: BorrowServiceStub;
  let bookService: BookServiceStub;
  let customerService: CustomerServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: BorrowCreatePage;
  let fixture: ComponentFixture<BorrowCreatePage>;

  beforeEach(async () => {
    borrowService = new BorrowServiceStub();
    bookService = new BookServiceStub();
    customerService = new CustomerServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [BorrowCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: BorrowService, useValue: borrowService },
        { provide: BookService, useValue: bookService },
        { provide: CustomerService, useValue: customerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BorrowCreatePage);
    fixture.componentRef.setInput('sequenceNumber', 'P-00015');
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
    expect(component.lines).toHaveLength(0);
  });

  it('should run when add line', () => {
    fixture.detectChanges();

    component.onAddLine();

    expect(component.lines).toHaveLength(1);
  });

  it('should fail when persist and wrong lines', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onCreate();
    fixture.detectChanges();

    expect(component.form.invalid).toBeTruthy();
    expect(component.lines).toHaveLength(1);
    expect(borrowService.createPayload).toBeNull();
  });

  it('should run when remove line', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onRemoveLine(0);

    expect(component.lines).toHaveLength(0);
  });

  it('should open customer dialog when keydown on the customer input', () => {
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'borrow-customer');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.customerSelectorVisible()).toBeTruthy();
  });

  it('should run when select customer', () => {
    fixture.detectChanges();

    const customer = customerService.get(Ref.CustomerJohnDoe);
    component.onSelectCustomer(customer);
    fixture.detectChanges();

    expect(component.form.get('customer')?.value).toEqual({
      id: customer.id,
      fullName: customer.fullName,
      number: customer.membership.number,
    });
  });

  it('should open book dialog when keydown on the book input', () => {
    fixture.detectChanges();

    component.onAddLine();
    const event = new KeyboardEvent('keydown', { key: '/' });
    component.onBookKeydown(event, 0);
    fixture.detectChanges();

    expect(component.bookSelectorVisible()).toBeTruthy();
  });

  it('should fail when not line index on select book', () => {
    fixture.detectChanges();

    const book = BookMother.romeoAndJuliet();
    component.onSelectBook(book);
    fixture.detectChanges();

    expect(component.lines).toHaveLength(0);
  });

  it('should fail when has not line control', () => {
    fixture.detectChanges();

    component.onSelectClicked(0);
    const book = BookMother.romeoAndJuliet();
    component.onSelectBook(book);
    fixture.detectChanges();

    expect(component.lines).toHaveLength(0);
  });

  it('should run when fill book', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onSelectClicked(0);
    const book = BookMother.romeoAndJuliet();
    component.onSelectBook(book);
    fixture.detectChanges();

    expect(component.lines).toHaveLength(1);
    const line = component.lines.at(0).get('book')!;
    expect(line.get('id')?.value).toEqual(book.id);
  });

  it('should mark error when book is not available', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onSelectClicked(0);
    const book = BookMother.romeoAndJuliet();
    bookService.available = false;
    component.onSelectBook(book);
    fixture.detectChanges();

    expect(component.lines).toHaveLength(1);
    const line = component.lines.at(0).get('book')!;
    expect(line.get('id')?.value).toEqual(book.id);
  });

  it('should run when create borrow', () => {
    fixture.detectChanges();

    component.onAddLine();
    const book = BookMother.romeoAndJuliet();
    component.onSelectClicked(0);
    component.onSelectBook(book);

    const customer = CustomerMother.johnDoe({ surname: '' });
    component.form.patchValue({
      customer: {
        id: customer.id,
        fullName: customer.fullName,
        number: customer.membership.number,
      },
    });

    component.onCreate();
    fixture.detectChanges();

    expect(component.form.valid).toBeTruthy();
    expect(borrowService.createPayload).not.toBeNull();
  });
});
