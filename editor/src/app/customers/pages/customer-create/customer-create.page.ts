import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Divider } from 'primeng/divider';
import { Steps } from 'primeng/steps';
import { Chip } from 'primeng/chip';
// Models
import { Customer } from '@/customers/model/customer';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
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
    FaIconComponent,
    InputText,
    Message,
    Divider,
    Steps,
    Chip,
  ],
  templateUrl: './customer-create.page.html',
  styleUrl: './customer-create.page.less',
})
export default class CustomerCreatePage extends AbstractCreatePage<Customer> {
  private customerService = inject(CustomerService);

  breadcrumb: MenuItem[] = [
    {
      label: 'Clientes',
      routerLink: '/customers',
    },
    { label: 'Crear Cliente', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();

    this.addStep(
      'Detalle y Contacto',
      ['name', 'contact.email'],
      ['name', 'surname', 'contact.email'],
    );
    this.addStep('Dirección', [], ['address.city', 'address.province']);
    this.addStep('Datos fiscales');
  }

  override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      surname: new FormControl(''),
      vatNumber: new FormControl(''),
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

  onCreate(): void {
    this.persist();
  }

  protected getCreatedData(): CreatorData<Customer> {
    return new CreatorData<Customer>(
      this.customerService,
      EntityMessages.create('Cliente', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/customers'];
  }
}
