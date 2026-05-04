import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { PurchaseServiceStub } from '@tests/doubles/services/purchase-service.stub';
import { BookServiceStub } from '@tests/doubles/services/book-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { BookService } from '@/books/services/book-service';
import { PurchaseService } from '@/purchase/services/purchase.service';
import PurchaseEditPage from '@/purchase/pages/purchase-edit/purchase-edit.page';

describe('PurchaseEditPage', () => {
  let purchaseService: PurchaseServiceStub;
  let bookService: BookServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: PurchaseEditPage;
  let fixture: ComponentFixture<PurchaseEditPage>;

  beforeEach(async () => {
    purchaseService = new PurchaseServiceStub();
    bookService = new BookServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [PurchaseEditPage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: PurchaseService, useValue: purchaseService },
        { provide: BookService, useValue: bookService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(PurchaseEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
    expect(component.lines).toHaveLength(2);
  });

  it('should run when add line', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    component.onAddLine();

    expect(component.lines).toHaveLength(3);
  });

  it('should run when remove line', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    component.onRemoveLine(0);

    expect(component.lines).toHaveLength(1);
  });

  it('should book dialog open when keydown on the book input', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    component.onAddLine();
    const event = new KeyboardEvent('keydown', { key: '/' });
    component.onBookKeydown(event, 0);

    expect(component.bookSelectorVisible()).toBeTruthy();
  });

  it('should fail when no line on select book', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    const book = bookService.get(Ref.BookDonQuixote);
    component.onSelectBook(book);

    expect(component.lines).toHaveLength(2);

    component.onSelectClicked(3);
    component.onSelectBook(book);

    expect(component.lines).toHaveLength(2);
  });

  it('should run when select book', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

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

  it('should run when update purchase', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    component.onSave();

    expect(purchaseService.updatePayload).not.toBeNull();
  });
});
