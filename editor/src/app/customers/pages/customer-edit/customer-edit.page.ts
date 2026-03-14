import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  OnInit,
} from '@angular/core';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
// Framework
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { Divider } from 'primeng/divider';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { ToggleButton } from 'primeng/togglebutton';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Customer } from '@/customers/model/customer';
import { StorerData } from '@/shared/models/storer-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { CustomerService } from '@/customers/services/customer.service';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    TabsModule,
    Divider,
    InputText,
    Message,
    FaIconComponent,
    ToggleButton,
  ],
  templateUrl: './customer-edit.page.html',
  styleUrl: './customer-edit.page.less',
})
export default class CustomerEditPage
  extends AbstractEditPage<Customer>
  implements OnInit
{
  private customerService = inject(CustomerService);

  customer = input.required<Customer>();

  breadcrumb: MenuItem[] = [
    { label: 'Clientes', routerLink: '/customers' },
    { label: 'Editar Cliente', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      surname: new FormControl(''),
      vatNumber: new FormControl(''),
      membership: new FormGroup({
        active: new FormControl(true),
      }),
      contact: new FormGroup({
        email: new FormControl('', [Validators.email]),
        phone1: new FormControl(''),
        phone2: new FormControl(''),
      }),
      address: new FormGroup({
        street: new FormControl(''),
        postalCode: new FormControl(''),
        city: new FormControl(''),
        province: new FormControl(''),
        country: new FormControl(''),
      }),
    });
  }

  ngOnInit() {
    this.fillForm(this.customer());
  }

  onSave(): void {
    this.persist();
  }

  protected getStoredData(): StorerData<Customer> {
    return new StorerData<Customer>(
      this.customer(),
      this.customerService,
      EntityMessages.edit('Cliente', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/customers', this.customer().id];
  }
}
