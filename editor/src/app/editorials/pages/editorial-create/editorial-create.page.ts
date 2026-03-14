import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Divider } from 'primeng/divider';
import { Steps } from 'primeng/steps';
import { Chip } from 'primeng/chip';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
// Models
import { Editorial } from '@/editorials/model/editorial';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { EditorialService } from '@/editorials/services/editorial.service';
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
  templateUrl: './editorial-create.page.html',
  styleUrl: './editorial-create.page.less',
})
export default class EditorialCreatePage extends AbstractCreatePage<Editorial> {
  private readonly webPattern =
    /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-]*)*(\?.*)?(#.*)?$/;

  private editorialService = inject(EditorialService);

  breadcrumb: MenuItem[] = [
    {
      label: 'Editoriales',
      routerLink: '/editorials',
    },
    { label: 'Crear Editorial', styleClass: 'text-xl font-bold' },
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

  protected getCreatedData(): CreatorData<Editorial> {
    return new CreatorData<Editorial>(
      this.editorialService,
      EntityMessages.create('Editorial', NounGenre.female),
    );
  }

  protected override getBackPath(): string[] {
    return ['/editorials'];
  }
}
