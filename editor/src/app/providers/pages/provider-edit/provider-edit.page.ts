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
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Divider } from 'primeng/divider';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Provider } from '@/providers/model/provider';
import { StorerData } from '@/shared/models/storer-data';
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
    TabsModule,
    InputText,
    Message,
    Divider,
  ],
  templateUrl: './provider-edit.page.html',
  styleUrl: './provider-edit.page.less',
})
export default class ProviderEditPage
  extends AbstractEditPage<Provider>
  implements OnInit
{
  private readonly webPattern =
    /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-]*)*(\?.*)?(#.*)?$/;

  private providerService = inject(ProviderService);

  provider = input.required<Provider>();

  breadcrumb: MenuItem[] = [
    { label: 'Proveedores', routerLink: '/providers' },
    { label: 'Editar Proveedor', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      comercialName: new FormControl(''),
      vatNumber: new FormControl(''),
      contact: new FormGroup({
        email: new FormControl('', [Validators.email]),
        website: new FormControl('', [Validators.pattern(this.webPattern)]),
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
    this.fillForm(this.provider());
  }

  onSave() {
    this.persist();
  }

  protected getStoredData(): StorerData<Provider> {
    return new StorerData<Provider>(
      this.provider(),
      this.providerService,
      EntityMessages.edit('Proveedor', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/providers', this.provider().id];
  }
}
