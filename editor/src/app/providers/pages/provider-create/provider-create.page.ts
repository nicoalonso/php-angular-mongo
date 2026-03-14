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
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Divider } from 'primeng/divider';
import { Steps } from 'primeng/steps';
import { Chip } from 'primeng/chip';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
// Models
import { Provider } from '@/providers/model/provider';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { ProviderService } from '@/providers/services/provider.service';
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
  templateUrl: './provider-create.page.html',
  styleUrl: './provider-create.page.less',
})
export default class ProviderCreatePage extends AbstractCreatePage<Provider> {
  private readonly webPattern =
    /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-]*)*(\?.*)?(#.*)?$/;

  private providerService = inject(ProviderService);

  breadcrumb: MenuItem[] = [
    {
      label: 'Proveedores',
      routerLink: '/providers',
    },
    { label: 'Crear Proveedor', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();

    this.addStep(
      'Detalle y Contacto',
      ['name', 'contact.email', 'contact.website'],
      ['name', 'comercialName', 'contact.website'],
    );
    this.addStep('Dirección');
  }

  protected override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      comercialName: new FormControl(''),
      vatNumber: new FormControl(''),
      address: new FormGroup({
        street: new FormControl(''),
        postalCode: new FormControl(''),
        city: new FormControl(''),
        province: new FormControl(''),
        country: new FormControl(''),
      }),
      contact: new FormGroup({
        email: new FormControl('', [Validators.email]),
        website: new FormControl('', [Validators.pattern(this.webPattern)]),
        phone1: new FormControl(''),
        phone2: new FormControl(''),
      }),
    });
  }

  onCreate(): void {
    this.persist();
  }

  protected getCreatedData(): CreatorData<Provider> {
    return new CreatorData<Provider>(
      this.providerService,
      EntityMessages.create('Proveedor', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/providers'];
  }
}
