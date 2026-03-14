import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
import dayjs from 'dayjs';
// Framework
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { Steps } from 'primeng/steps';
import { Chip } from 'primeng/chip';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { DatePicker } from 'primeng/datepicker';
// Pages
import { AbstractCreatePage } from '@/shared/pages/abstract-create/abstract-create.page';
// Models
import { Author } from '@/authors/model/author';
import { CreatorData } from '@/shared/models/creator-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
import { EntityPayload } from '@/shared/models/entity';
// Services
import { AuthorService } from '@/authors/services/author.service';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    Steps,
    Chip,
    FaIconComponent,
    InputText,
    Message,
    DatePicker,
  ],
  templateUrl: './author-create.page.html',
  styleUrl: './author-create.page.less',
})
export default class AuthorCreatePage extends AbstractCreatePage<Author> {
  protected readonly maxDate = new Date();

  private authorService = inject(AuthorService);

  breadcrumb: MenuItem[] = [
    {
      label: 'Autores',
      routerLink: '/authors',
    },
    { label: 'Crear Autor', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();

    this.addStep('Detalle', ['name'], ['name', 'realName']);
    this.addStep('Datos Personales', ['birthDate', 'photoUrl', 'website']);
  }

  override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      realName: new FormControl(''),
      genres: new FormControl(''),
      biography: new FormControl(''),
      nationality: new FormControl(''),
      deathDate: new FormControl(null),
      birthDate: new FormControl(null, [Validators.required]),
      photoUrl: new FormControl('', [
        Validators.pattern(/^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|svg|webp))$/i),
      ]),
      website: new FormControl('', [
        Validators.pattern(
          /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-]*)*(\?.*)?(#.*)?$/,
        ),
      ]),
    });
  }

  protected override processForm(data: object): EntityPayload {
    const { birthDate, deathDate, ...rest } = data as Author;
    return {
      ...rest,
      birthDate: dayjs(birthDate).format('YYYY-MM-DD'),
      deathDate: deathDate ? dayjs(deathDate).format('YYYY-MM-DD') : null,
    };
  }

  onCreate(): void {
    this.persist();
  }

  protected getCreatedData(): CreatorData<Author> {
    return new CreatorData<Author>(
      this.authorService,
      EntityMessages.create('Autor', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/authors'];
  }
}
