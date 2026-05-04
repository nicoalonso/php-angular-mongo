import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { SaleServiceStub } from '@tests/doubles/services/sale-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { BookService } from '@/books/services/book-service';
import { CustomerService } from '@/customers/services/customer.service';
import { SaleService } from '@/sales/services/sale.service';
import SaleCreatePage from './sale-create.page';
import { findById } from '@tests/helpers/search-dom';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { BookMother } from '@tests/fixtures/mothers/book.mother';

describe('SaleCreatePage', () => {
  let saleService: SaleServiceStub;
  let bookService: BookServiceStub;
  let customerService: CustomerServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: SaleCreatePage;
  let fixture: ComponentFixture<SaleCreatePage>;

  beforeEach(async () => {
    saleService = new SaleServiceStub();
    bookService = new BookServiceStub();
    customerService = new CustomerServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [SaleCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: SaleService, useValue: saleService },
        { provide: BookService, useValue: bookService },
        { provide: CustomerService, useValue: customerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(SaleCreatePage);
    fixture.componentRef.setInput('sequenceNumber', 'V-00015');
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(component.lines).toHaveLength(0);
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add line', () => {
    fixture.detectChanges();

    component.onAddLine();

    expect(component.lines).toHaveLength(1);
  });

  it('should rum when remove line', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onAddLine();

    expect(component.lines).toHaveLength(2);

    component.onRemoveLine(0);

    expect(component.lines).toHaveLength(1);
  });

  it('should open customer dialog when keydown on the customer input', () => {
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'sale-customer');

    fireEvent.keyDown(input, { key: '/' });
    fixture.detectChanges();

    expect(component.customerSelectorVisible()).toBeTruthy();
  });

  it('should run when select customer', () => {
    fixture.detectChanges();

    const customer = customerService.get(Ref.CustomerJohnDoe);
    component.onSelectCustomer(customer);

    expect(component.form.get('customer')?.value).toEqual({
      id: customer.id,
      fullName: customer.fullName,
      vatNumber: customer.vatNumber,
    });
  });

  it('should open book dialog when keydown on the book input', () => {
    fixture.detectChanges();

    component.onAddLine();
    const event = new KeyboardEvent('keydown', { key: '/' });
    component.onBookKeydown(event, 1);
    fixture.detectChanges();

    expect(component.bookSelectorVisible()).toBeTruthy();
  });

  it('should fail when not line index on select book', () => {
    fixture.detectChanges();

    const book = BookMother.romeoAndJuliet();
    component.onSelectBook(book);
    fixture.detectChanges();

    expect(component.lines).toHaveLength(0);

    component.onSelectClicked(0);
    component.onSelectBook(book);

    expect(component.lines).toHaveLength(0);
  });

  it('should run when fill book', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onSelectClicked(0);
    const book = BookMother.romeoAndJuliet();
    component.onSelectBook(book);

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

    expect(component.lines).toHaveLength(1);
    const line = component.lines.at(0).get('book')!;
    expect(line.get('id')?.value).toEqual(book.id);
  });

  it('should run when save', () => {
    fixture.detectChanges();

    const customer = customerService.get(Ref.CustomerJohnDoe);
    component.onSelectCustomer(customer);

    component.onAddLine();
    component.onSelectClicked(0);
    const book = BookMother.donQuixote();
    component.onSelectBook(book);

    component.onCreate();

    expect(component.form.valid).toBeTruthy();
    expect(saleService.createPayload).not.toBeNull();
  });
});
