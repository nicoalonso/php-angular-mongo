import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  model,
  OnDestroy,
} from '@angular/core';
import {
  FormArray,
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
import { combineLatest, startWith, Subscription } from 'rxjs';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import dayjs from 'dayjs';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem, PrimeTemplate } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputNumber } from 'primeng/inputnumber';
import { InputGroup } from 'primeng/inputgroup';
import { InputGroupAddon } from 'primeng/inputgroupaddon';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { TableModule } from 'primeng/table';
import { Tooltip } from 'primeng/tooltip';
import { DatePicker } from 'primeng/datepicker';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
// Models
import { Sale } from '@/sales/model/sale';
import { SaleLine } from '@/sales/model/sale-line';
import { Customer } from '@/customers/model/customer';
import { Book } from '@/books/model/book';
import { EntityPayload } from '@/shared/models/entity';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { SaleService } from '@/sales/services/sale.service';
import { BookService } from '@/books/services/book-service';
// Dialogs
import { CustomerDialogComponent } from '@/customers/dialogs/customer-dialog/customer-dialog.component';
import { BookDialogComponent } from '@/books/dialog/book-dialog/book-dialog.component';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';
import { emptyLinesValidator } from '@/purchase/validators/empty-lines.validator';
import { availableValidator } from '@/shared/validators/available-validator';

const TAX_RATE: number = 21.0;

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    BookDialogComponent,
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    FaIconComponent,
    InputNumber,
    InputGroup,
    InputGroupAddon,
    InputText,
    Message,
    PrimeTemplate,
    TableModule,
    Tooltip,
    DatePicker,
    CustomerDialogComponent,
  ],
  templateUrl: './sale-create.page.html',
  styleUrl: './sale-create.page.less',
})
export default class SaleCreatePage
  extends AbstractCreatePage<Sale>
  implements OnDestroy
{
  public readonly maxDate = new Date();

  private saleService = inject(SaleService);
  private bookService = inject(BookService);

  sequenceNumber = input.required<string>();
  customerSelectorVisible = model(false);
  bookSelectorVisible = model(false);

  breadcrumb: MenuItem[] = [
    {
      label: 'Ventas',
      routerLink: '/sales',
    },
    { label: 'Crear Venta', styleClass: 'text-xl font-bold' },
  ];

  private lineIndex: number | null = null;
  private linesChangesSub: Subscription[] = [];

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      customer: new FormGroup({
        id: new FormControl('', [emptyValidator]),
        fullName: new FormControl('', [emptyValidator]),
        vatNumber: new FormControl(''),
      }),
      invoice: new FormGroup({
        date: new FormControl(new Date(), [Validators.required]),
        amount: new FormControl(0),
        taxPercentage: new FormControl(TAX_RATE),
        taxes: new FormControl(0),
        total: new FormControl(0),
      }),
      lines: new FormArray([], [emptyLinesValidator]),
    });

    this.calculateInvoice();
  }

  get lines(): FormArray {
    return this.form.get('lines') as FormArray;
  }

  ngOnDestroy() {
    this.linesChangesSub.forEach((sub) => sub.unsubscribe());
  }

  private calculateInvoice(): void {
    this.lines.valueChanges
      .pipe(takeUntilDestroyed())
      .subscribe((data: SaleLine[]) => {
        const amount = data.reduce((sum, line) => sum + line.total, 0);
        this.form.get('invoice')!.patchValue({ amount });
      });

    combineLatest({
      amount: this.form.get('invoice.amount')!.valueChanges.pipe(startWith(0)),
      taxPercentage: this.form
        .get('invoice.taxPercentage')!
        .valueChanges.pipe(startWith(TAX_RATE)),
    })
      .pipe(takeUntilDestroyed())
      .subscribe(({ amount, taxPercentage }) => {
        const taxes = amount * (taxPercentage / 100);
        this.form.get('invoice.taxes')!.patchValue(taxes);
      });

    this.form
      .get('invoice.taxes')!
      .valueChanges.pipe(startWith(0))
      .subscribe((taxes) => {
        const amount = this.form.get('invoice.amount')!.value;
        const total = amount + taxes;
        this.form.get('invoice.total')!.patchValue(total);
      });
  }

  onCreate(): void {
    this.persist();
  }

  protected override processForm(data: object): EntityPayload {
    const { customer, invoice, lines } = data as Sale;

    return {
      customerId: customer.id,
      invoice: {
        date: dayjs(invoice.date).format('YYYY-MM-DD'),
        amount: invoice.amount,
        taxPercentage: invoice.taxPercentage,
        taxes: invoice.taxes,
        total: invoice.total,
      },
      lines: lines.map((line) => ({
        lineId: line.lineId,
        bookId: line.book.id,
        quantity: line.quantity,
        price: line.price,
        discount: line.discount,
        total: line.total,
      })),
    };
  }

  onAddLine(): void {
    const line = SaleLine.empty();
    this.addLine(line);
  }

  private addLine(line: SaleLine): void {
    const lineForm = new FormGroup({
      lineId: new FormControl(''),
      book: new FormGroup({
        id: new FormControl(line.book.id, [emptyValidator]),
        title: new FormControl(line.book.title, [emptyValidator]),
        isbn: new FormControl(line.book.isbn),
        available: new FormControl(true, [availableValidator]),
      }),
      quantity: new FormControl(line.quantity),
      price: new FormControl(line.price, [Validators.min(0)]),
      discount: new FormControl(line.discount, [Validators.min(0)]),
      total: new FormControl(line.total),
    });
    this.calculateLine(lineForm, line);
    this.lines.push(lineForm);
  }

  private calculateLine(lineForm: FormGroup, line: SaleLine): void {
    const subs = combineLatest({
      quantity: lineForm
        .get('quantity')!
        .valueChanges.pipe(startWith(line.quantity)),
      price: lineForm.get('price')!.valueChanges.pipe(startWith(line.price)),
      discount: lineForm
        .get('discount')!
        .valueChanges.pipe(startWith(line.discount)),
    }).subscribe(({ quantity, price, discount }) => {
      const total = quantity * price * (1 - discount / 100);
      lineForm.get('total')!.setValue(total, { emitEvent: false });
    });

    this.linesChangesSub.push(subs);
  }

  onRemoveLine(index: number): void {
    this.lines.removeAt(index);
    this.linesChangesSub[index].unsubscribe();
    this.linesChangesSub.splice(index, 1);
  }

  onCustomerKeydown(event: KeyboardEvent): void {
    if (event.key === '/') {
      event.preventDefault();
      this.customerSelectorVisible.set(true);
    }
  }

  onSelectCustomer(customer: Customer) {
    this.form.get('customer')!.patchValue({
      id: customer.id,
      fullName: customer.fullName,
      vatNumber: customer.vatNumber,
    });
  }

  onBookKeydown(event: KeyboardEvent, index: number): void {
    if (event.key === '/') {
      event.preventDefault();
      this.onSelectClicked(index);
    }
  }

  onSelectClicked(index: number): void {
    this.lineIndex = index;
    this.bookSelectorVisible.set(true);
  }

  onSelectBook(book: Book): void {
    if (null === this.lineIndex) {
      return;
    }
    const control = this.lines.at(this.lineIndex) as FormGroup;
    if (!control) {
      return;
    }

    this.bookService.checkAvailability(book.id, true).subscribe((available) => {
      this.fillBook(control, book, available);
    });
  }

  protected fillBook(control: FormGroup, book: Book, available: boolean): void {
    // Mark as dirty and touched to show validation errors if the book is not available
    if (!available) {
      const controlAvailable = control.get('book.available');
      controlAvailable?.markAsDirty();
      controlAvailable?.markAsTouched();
    }

    control.patchValue({
      book: {
        id: book.id,
        title: book.title,
        isbn: book.detail.isbn,
        available: available,
      },
      price: book.sale.price,
      discount: book.sale.discount,
    });
  }

  protected getCreatedData(): CreatorData<Sale> {
    return new CreatorData<Sale>(
      this.saleService,
      EntityMessages.create('Venta', NounGenre.female),
    );
  }

  protected override getBackPath(): string[] {
    return ['/sales'];
  }
}
