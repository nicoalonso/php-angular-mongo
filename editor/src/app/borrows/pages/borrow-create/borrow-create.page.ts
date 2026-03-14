import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  model,
  signal,
} from '@angular/core';
import {
  FormArray,
  FormControl,
  FormGroup,
  ReactiveFormsModule,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
import dayjs from 'dayjs';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem, PrimeTemplate } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputGroup } from 'primeng/inputgroup';
import { InputGroupAddon } from 'primeng/inputgroupaddon';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Tooltip } from 'primeng/tooltip';
import { TableModule } from 'primeng/table';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
// Models
import { Borrow } from '@/borrows/model/borrow';
import { BorrowLine } from '@/borrows/model/borrow-line';
import { Customer } from '@/customers/model/customer';
import { Book } from '@/books/model/book';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
import { EntityPayload } from '@/shared/models/entity';
// Services
import { BorrowService } from '@/borrows/services/borrow.service';
import { BookService } from '@/books/services/book-service';
// Dialogs
import { CustomerDialogComponent } from '@/customers/dialogs/customer-dialog/customer-dialog.component';
import { BookDialogComponent } from '@/books/dialog/book-dialog/book-dialog.component';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';
import { emptyLinesValidator } from '@/purchase/validators/empty-lines.validator';
import { availableValidator } from '@/shared/validators/available-validator';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    FaIconComponent,
    InputGroup,
    InputGroupAddon,
    InputText,
    Message,
    Tooltip,
    CustomerDialogComponent,
    PrimeTemplate,
    TableModule,
    BookDialogComponent,
  ],
  templateUrl: './borrow-create.page.html',
  styleUrl: './borrow-create.page.less',
})
export default class BorrowCreatePage extends AbstractCreatePage<Borrow> {
  protected readonly today: string = dayjs().format('DD/MM/YYYY');
  protected readonly dueDate: string = dayjs()
    .add(14, 'day')
    .format('DD/MM/YYYY');

  private borrowService = inject(BorrowService);
  private bookService = inject(BookService);

  sequenceNumber = input.required<string>();
  customerSelectorVisible = model(false);
  bookSelectorVisible = model(false);
  totalBooks = signal<number>(0);

  breadcrumb: MenuItem[] = [
    {
      label: 'Préstamos',
      routerLink: '/borrows',
    },
    { label: 'Crear Préstamo', styleClass: 'text-xl font-bold' },
  ];

  private lineIndex: number | null = null;

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      customer: new FormGroup({
        id: new FormControl('', [emptyValidator]),
        fullName: new FormControl('', [emptyValidator]),
        number: new FormControl(''),
      }),
      lines: new FormArray([], [emptyLinesValidator]),
    });
  }

  get lines(): FormArray {
    return this.form.get('lines') as FormArray;
  }

  onCreate(): void {
    this.persist();
  }

  protected override processForm(data: object): EntityPayload {
    const { customer, lines } = data as Borrow;

    return {
      customerId: customer.id,
      lines: lines.map((line) => ({
        lineId: line.lineId,
        bookId: line.book.id,
      })),
    };
  }

  onAddLine(): void {
    const line = BorrowLine.empty();
    this.addLine(line);
  }

  private addLine(line: BorrowLine): void {
    const lineForm = new FormGroup({
      lineId: new FormControl(''),
      book: new FormGroup({
        id: new FormControl(line.book.id, [emptyValidator]),
        title: new FormControl(line.book.title, [emptyValidator]),
        isbn: new FormControl(line.book.isbn),
        author: new FormControl(''),
        editorial: new FormControl(''),
        stock: new FormControl(0),
        available: new FormControl(true, [availableValidator]),
      }),
    });
    this.lines.push(lineForm);
    this.totalBooks.set(this.lines.length);
  }

  onRemoveLine(index: number): void {
    this.lines.removeAt(index);
    this.totalBooks.set(this.lines.length);
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
      number: customer.membership.number,
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

    this.bookService
      .checkAvailability(book.id, false)
      .subscribe((available) => {
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
        author: book.author.name,
        editorial: book.editorial.name,
        stock: book.stock,
        available: available,
      },
    });
  }

  protected getCreatedData(): CreatorData<Borrow> {
    return new CreatorData<Borrow>(
      this.borrowService,
      EntityMessages.create('Préstamo', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/borrows'];
  }
}
