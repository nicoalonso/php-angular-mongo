import {
  ChangeDetectionStrategy,
  Component,
  effect,
  ElementRef,
  inject,
  input,
  model,
  output,
  signal,
  viewChild,
} from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';
// Framework
import { Dialog } from 'primeng/dialog';
import { Button } from 'primeng/button';
import { InputGroup } from 'primeng/inputgroup';
import { InputText } from 'primeng/inputtext';
import { IftaLabel } from 'primeng/iftalabel';
import { InputGroupAddon } from 'primeng/inputgroupaddon';
import { Tooltip } from 'primeng/tooltip';
import { TableModule } from 'primeng/table';
// Models
import { Customer } from '@/customers/model/customer';
// Services
import { CustomerService } from '@/customers/services/customer.service';

@Component({
  selector: 'app-customer-dialog',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    Dialog,
    Button,
    InputGroup,
    ReactiveFormsModule,
    InputText,
    IftaLabel,
    InputGroupAddon,
    Tooltip,
    TableModule,
  ],
  templateUrl: './customer-dialog.component.html',
  styleUrl: './customer-dialog.component.less',
})
export class CustomerDialogComponent {
  customerService = inject(CustomerService);

  visible = model.required<boolean>();
  activeCustomers = input<boolean>(false);
  selected = output<Customer>();
  nameInput = viewChild<ElementRef<HTMLInputElement>>('nameInput');
  loading = signal(false);
  customers = signal<Customer[]>([]);

  form: FormGroup;
  initialOpen = true;

  constructor() {
    this.form = new FormGroup({
      name: new FormControl(''),
      surname: new FormControl(''),
      number: new FormControl(''),
      vatNumber: new FormControl(''),
    });

    effect(() => {
      if (this.visible()) {
        setTimeout(() => {
          this.nameInput()?.nativeElement.select();
          this.nameInput()?.nativeElement.focus();
        }, 450);
      }

      if (this.initialOpen) {
        this.initialOpen = false;
        this.onSearch();
      }
    });
  }

  onSearch() {
    const options = new Map();
    const filters = this.form.getRawValue();

    Object.keys(filters)
      .filter((key) => filters[key])
      .forEach((key) => options.set(key, filters[key]));

    if (this.activeCustomers()) {
      options.set('active', true);
    }

    if (options.size == 0) {
      options.set('sort', '+createdAt');
    }

    const query = Object.fromEntries(options);
    this.loading.set(true);
    this.customerService.search(query).subscribe((result) => {
      this.customers.set(result.items);
      this.loading.set(false);
    });
  }

  onClickItem(customer: Customer, $event: MouseEvent): void {
    $event.stopPropagation();
    this.selected.emit(customer);
    this.visible.set(false);
  }
}
