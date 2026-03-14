import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  model,
  OnDestroy,
  OnInit,
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
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputText } from 'primeng/inputtext';
import { DatePicker } from 'primeng/datepicker';
import { Message } from 'primeng/message';
import { InputNumber } from 'primeng/inputnumber';
import { TableModule } from 'primeng/table';
import { InputGroup } from 'primeng/inputgroup';
import { InputGroupAddon } from 'primeng/inputgroupaddon';
import { Tooltip } from 'primeng/tooltip';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Purchase } from '@/purchase/model/purchase';
import { PurchaseLine } from '@/purchase/model/purchase-line';
import { Book } from '@/books/model/book';
import { StorerData } from '@/shared/models/storer-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
import { EntityPayload } from '@/shared/models/entity';
// Services
import { PurchaseService } from '@/purchase/services/purchase.service';
// Dialogs
import { BookDialogComponent } from '@/books/dialog/book-dialog/book-dialog.component';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';
import { emptyLinesValidator } from '@/purchase/validators/empty-lines.validator';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    InputText,
    DatePicker,
    Message,
    InputNumber,
    TableModule,
    BookDialogComponent,
    InputGroup,
    InputGroupAddon,
    Tooltip,
  ],
  templateUrl: './purchase-edit.page.html',
  styleUrl: './purchase-edit.page.less',
})
export default class PurchaseEditPage
  extends AbstractEditPage<Purchase>
  implements OnInit, OnDestroy
{
  private readonly taxRate: number = 21;
  public readonly maxDate = new Date();

  private purchaseService = inject(PurchaseService);

  purchase = input.required<Purchase>();
  bookSelectorVisible = model(false);

  breadcrumb: MenuItem[] = [
    { label: 'Libros', routerLink: '/books' },
    { label: 'Editar Cliente', styleClass: 'text-xl font-bold' },
  ];
  private initialForm = true;
  private lineIndex: number | null = null;
  private linesChangesSub: Subscription[] = [];

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      provider: new FormGroup({
        id: new FormControl(''),
        name: new FormControl(''),
      }),
      purchasedAt: new FormControl(null, [Validators.required]),
      invoice: new FormGroup({
        number: new FormControl('', [emptyValidator]),
        amount: new FormControl(0),
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

  ngOnInit() {
    this.fillForm(this.purchase());
    this.initialForm = false;
  }

  ngOnDestroy() {
    this.linesChangesSub.forEach((sub) => sub.unsubscribe());
  }

  protected override fillForm(entity: Purchase) {
    const { lines, ...purchaseData } = entity;
    this.form.patchValue(purchaseData);

    if (Array.isArray(lines)) {
      for (const line of lines) {
        this.addLine(line);
      }
    }
  }

  private calculateInvoice(): void {
    this.lines.valueChanges
      .pipe(takeUntilDestroyed())
      .subscribe((data: PurchaseLine[]) => {
        if (this.initialForm) {
          return;
        }

        const amount = data.reduce((sum, line) => sum + line.total, 0);
        const taxes = amount * (this.taxRate / 100);
        this.form.get('invoice')!.patchValue({ amount, taxes });
      });

    combineLatest({
      amount: this.form.get('invoice.amount')!.valueChanges.pipe(startWith(0)),
      taxes: this.form.get('invoice.taxes')!.valueChanges.pipe(startWith(0)),
    })
      .pipe(takeUntilDestroyed())
      .subscribe(({ amount, taxes }) => {
        if (this.initialForm) {
          return;
        }

        const total = amount + taxes;
        this.form.get('invoice.total')!.patchValue(total);
      });
  }

  onSave(): void {
    this.persist();
  }

  protected override processForm(data: object): EntityPayload {
    const { provider, purchasedAt, invoice, lines } = data as Purchase;

    return {
      providerId: provider.id,
      purchasedAt: dayjs(purchasedAt).format('YYYY-MM-DD'),
      invoice,
      lines: lines.map((line) => ({
        lineId: line.lineId,
        bookId: line.book.id,
        quantity: line.quantity,
        unitPrice: line.unitPrice,
        discountPercentage: line.discountPercentage,
        total: line.total,
      })),
    };
  }

  onAddLine(): void {
    const line = PurchaseLine.empty();
    this.addLine(line);
  }

  private addLine(line: PurchaseLine): void {
    const lineForm = new FormGroup({
      lineId: new FormControl(line.lineId),
      book: new FormGroup({
        id: new FormControl(line.book.id, [emptyValidator]),
        title: new FormControl(line.book.title, [emptyValidator]),
        isbn: new FormControl(line.book.isbn),
      }),
      quantity: new FormControl(line.quantity, [Validators.min(0)]),
      unitPrice: new FormControl(line.unitPrice, [Validators.min(0)]),
      discountPercentage: new FormControl(line.discountPercentage, [
        Validators.min(0),
      ]),
      total: new FormControl(line.total, [Validators.min(0)]),
    });
    this.calculateLine(lineForm, line);
    this.lines.push(lineForm);
  }

  private calculateLine(lineForm: FormGroup, line: PurchaseLine): void {
    const subs = combineLatest({
      quantity: lineForm
        .get('quantity')!
        .valueChanges.pipe(startWith(line.quantity)),
      price: lineForm
        .get('unitPrice')!
        .valueChanges.pipe(startWith(line.unitPrice)),
      discount: lineForm
        .get('discountPercentage')!
        .valueChanges.pipe(startWith(line.discountPercentage)),
    }).subscribe(({ quantity, price, discount }) => {
      if (this.initialForm) {
        return;
      }

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

    const control = this.lines.at(this.lineIndex)?.get('book') as FormGroup;
    if (!control) {
      return;
    }

    control.patchValue({
      id: book.id,
      title: book.title,
      isbn: book.detail.isbn,
    });
  }

  protected getStoredData(): StorerData<Purchase> {
    return new StorerData<Purchase>(
      this.purchase(),
      this.purchaseService,
      EntityMessages.edit('Entrada', NounGenre.female),
    );
  }

  protected override getBackPath(): string[] {
    return ['/purchases', this.purchase().id];
  }
}
