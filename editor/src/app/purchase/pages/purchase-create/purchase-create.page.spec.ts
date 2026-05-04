import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { debounceTime } from 'rxjs';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { fireEvent } from '@testing-library/dom';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import { findById } from '@tests/helpers/search-dom';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { PurchaseServiceStub } from '@tests/doubles/services/purchase-service.stub';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { ProviderService } from '@/providers/services/provider.service';
import { BookService } from '@/books/services/book-service';
import { PurchaseService } from '@/purchase/services/purchase.service';
import PurchaseCreatePage from '@/purchase/pages/purchase-create/purchase-create.page';

describe('PurchaseCreatePage', () => {
  let purchaseService: PurchaseServiceStub;
  let providerService: ProviderServiceStub;
  let bookService: BookServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: PurchaseCreatePage;
  let fixture: ComponentFixture<PurchaseCreatePage>;

  beforeEach(async () => {
    purchaseService = new PurchaseServiceStub();
    providerService = new ProviderServiceStub();
    bookService = new BookServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [PurchaseCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: PurchaseService, useValue: purchaseService },
        { provide: ProviderService, useValue: providerService },
        { provide: BookService, useValue: bookService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(PurchaseCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add line', () => {
    fixture.detectChanges();

    component.onAddLine();

    expect(component.lines).toHaveLength(1);
  });

  it('should run when remove line', () => {
    fixture.detectChanges();

    component.onAddLine();
    component.onAddLine();

    expect(component.lines).toHaveLength(2);

    component.onRemoveLine(0);

    expect(component.lines).toHaveLength(1);
  });

  it('should calculate total when add line', (done) => {
    fixture.detectChanges();

    component.onAddLine();
    const line = component.lines.at(0);

    component.form
      .get('invoice.total')
      ?.valueChanges?.pipe(debounceTime(40))
      .subscribe((value) => {
        expect(line.get('total')?.value).toBe(18);
        expect(value).toBe(21.78);
        done();
      });

    line.patchValue({
      quantity: 2,
      unitPrice: 10,
      discountPercentage: 10,
    });
  });

  it('should provider dialog open when keydown on the provider input', () => {
    fixture.detectChanges();

    const input = findById<HTMLInputElement>(fixture, 'purchase-provider');
    fireEvent.keyDown(input, { key: '/' });

    expect(component.providerSelectorVisible()).toBeTruthy();
  });

  it('should run when select provider', () => {
    fixture.detectChanges();

    const provider = providerService.get(Ref.ProviderAmazon);
    component.onSelectProvider(provider);
    fixture.detectChanges();

    expect(component.form.get('provider')?.value).toEqual({
      id: provider.id,
      name: provider.name,
    });
  });

  it('should book dialog open when keydown on the book input', () => {
    fixture.detectChanges();

    component.onAddLine();
    const event = new KeyboardEvent('keydown', { key: '/' });
    component.onBookKeydown(event, 0);

    expect(component.bookSelectorVisible()).toBeTruthy();
  });

  it('should fail when no line on select book', () => {
    fixture.detectChanges();

    const book = bookService.get(Ref.BookDonQuixote);
    component.onSelectBook(book);

    expect(component.lines).toHaveLength(0);

    component.onSelectClicked(0);
    component.onSelectBook(book);

    expect(component.lines).toHaveLength(0);
  });

  it('should run when select book', () => {
    fixture.detectChanges();

    component.onAddLine();
    const book = bookService.get(Ref.BookDonQuixote);
    component.onSelectClicked(0);
    component.onSelectBook(book);
    fixture.detectChanges();

    const line = component.lines.at(0);
    expect(line?.get('book')?.value).toEqual({
      id: book.id,
      title: book.title,
      isbn: book.detail.isbn,
    });
  });

  it('should run when create purchase', () => {
    fixture.detectChanges();

    component.onAddLine();
    const book = bookService.get(Ref.BookDonQuixote);
    component.onSelectClicked(0);
    component.onSelectBook(book);
    const line = component.lines.at(0);
    line.patchValue({
      quantity: 2,
      unitPrice: 10,
      discountPercentage: 0,
      total: 20,
    });

    const provider = providerService.get(Ref.ProviderAmazon);
    component.onSelectProvider(provider);

    component.form.patchValue({
      purchasedAt: new Date(),
      invoice: {
        number: 'PUR-001',
        amount: 10,
        taxes: 2.1,
        total: 12.1,
      },
    });
    fixture.detectChanges();

    component.onCreate();

    expect(component.form.valid).toBeTruthy();
    expect(purchaseService.createPayload).not.toBeNull();
  });
});
