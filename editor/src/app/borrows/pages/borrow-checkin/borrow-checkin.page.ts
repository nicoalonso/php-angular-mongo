import {
  ChangeDetectionStrategy,
  Component,
  computed,
  inject,
  input,
  OnInit,
} from '@angular/core';
import {
  FormArray,
  FormControl,
  FormGroup,
  ReactiveFormsModule,
} from '@angular/forms';
import { NgClass } from '@angular/common';
import { RouterLink } from '@angular/router';
import dayjs from 'dayjs';
// Framework
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { MenuItem, PrimeTemplate } from 'primeng/api';
import { InputText } from 'primeng/inputtext';
import { TableModule } from 'primeng/table';
import { InputNumber } from 'primeng/inputnumber';
import { ToggleSwitch } from 'primeng/toggleswitch';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Borrow } from '@/borrows/model/borrow';
import { BorrowLine } from '@/borrows/model/borrow-line';
import { StorerData } from '@/shared/models/storer-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
import { EntityPayload } from '@/shared/models/entity';
import { BorrowCheckinPayload } from '@/borrows/model/borrow-checkin-payload';
// Services
import { BorrowService } from '@/borrows/services/borrow.service';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    InputText,
    NgClass,
    InputNumber,
    PrimeTemplate,
    TableModule,
    ToggleSwitch,
  ],
  templateUrl: './borrow-checkin.page.html',
  styleUrl: './borrow-checkin.page.less',
})
export default class BorrowCheckinPage
  extends AbstractEditPage<Borrow>
  implements OnInit
{
  private borrowService = inject(BorrowService);

  borrow = input.required<Borrow>();
  borrowDate = computed(() =>
    dayjs(this.borrow().borrowDate).format('DD/MM/YYYY'),
  );
  dueDate = computed(() => dayjs(this.borrow().dueDate).format('DD/MM/YYYY'));

  breadcrumb: MenuItem[] = [
    { label: 'Préstamos', routerLink: '/borrows' },
    { label: 'Retornar', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      penaltyAmount: new FormControl(0),
      lines: new FormArray([]),
    });
  }

  get lines(): FormArray {
    return this.form.get('lines') as FormArray;
  }

  ngOnInit() {
    this.fillForm(this.borrow());
  }

  protected override fillForm(entity: Borrow) {
    if (!Array.isArray(entity.lines)) {
      return;
    }

    this.form.patchValue({
      penaltyAmount: entity.penaltyAmount,
    });

    for (const line of entity.lines) {
      this.addLine(line);
    }
  }

  private addLine(line: BorrowLine): void {
    const lineForm = new FormGroup({
      lineId: new FormControl(line.lineId),
      book: new FormGroup({
        id: new FormControl(line.book.id),
        title: new FormControl(line.book.title),
        isbn: new FormControl(line.book.isbn),
      }),
      returned: new FormControl(line.returned),
      state: new FormControl(line.state),
      penaltyAmount: new FormControl(line.penaltyAmount),
    });
    this.lines.push(lineForm);
  }

  protected getLine(index: number): BorrowLine {
    return this.borrow().lines[index];
  }

  onSave(): void {
    if (!this.checkForm()) {
      this.toastService.topCenter().warn({
        summary: 'Existen errores en el formulario',
        detail: 'Revise los errores antes de continuar',
      });
      return;
    }

    const { messages } = this.getStoredData();
    const payload = this.processForm(
      this.form.getRawValue(),
    ) as BorrowCheckinPayload;

    this.borrowService.checkIn(this.borrow().id, payload).subscribe({
      next: () => {
        this.toastService.topRight().success({
          summary: messages.summary,
          detail: messages.detail,
        });
        this.goBack();
      },
      error: (error) => {
        console.error('Persist error', error);
        const { error: { message = '' } = {} } = error;
        this.toastService.topRight().error({
          summary: messages.error,
          detail: message,
          life: 10_000,
        });
      },
    });
  }

  protected override processForm(data: object): EntityPayload {
    const { lines } = data as Borrow;

    return {
      lines: lines.map((line) => ({
        lineId: line.lineId,
        bookId: line.book.id,
        returned: line.returned,
      })),
    };
  }

  protected getStoredData(): StorerData<Borrow> {
    return new StorerData<Borrow>(
      this.borrow(),
      this.borrowService,
      EntityMessages.edit('Préstamo', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/borrows', this.borrow().id];
  }
}
