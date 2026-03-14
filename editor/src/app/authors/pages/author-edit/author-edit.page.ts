import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  OnInit,
} from '@angular/core';
import { RouterLink } from '@angular/router';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import dayjs from 'dayjs';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem } from 'primeng/api';
import { TabsModule } from 'primeng/tabs';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { DatePicker } from 'primeng/datepicker';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Author } from '@/authors/model/author';
import { StorerData } from '@/shared/models/storer-data';
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
    TabsModule,
    Breadcrumb,
    Button,
    RouterLink,
    FaIconComponent,
    InputText,
    Message,
    DatePicker,
  ],
  templateUrl: './author-edit.page.html',
  styleUrl: './author-edit.page.less',
})
export default class AuthorEditPage
  extends AbstractEditPage<Author>
  implements OnInit
{
  public readonly maxDate = new Date();

  private authorService = inject(AuthorService);

  author = input.required<Author>();

  breadcrumb: MenuItem[] = [
    { label: 'Autores', routerLink: '/authors' },
    { label: 'Editar Autor', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      realName: new FormControl(''),
      genres: new FormControl(''),
      biography: new FormControl(''),
      nationality: new FormControl(''),
      birthDate: new FormControl(null, [Validators.required]),
      deathDate: new FormControl(null),
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

  ngOnInit() {
    this.fillForm(this.author());
  }

  protected override processForm(data: object): EntityPayload {
    const { birthDate, deathDate, ...rest } = data as Author;
    return {
      ...rest,
      birthDate: dayjs(birthDate).format('YYYY-MM-DD'),
      deathDate: deathDate ? dayjs(deathDate).format('YYYY-MM-DD') : null,
    };
  }

  onSave() {
    this.persist();
  }

  protected getStoredData(): StorerData<Author> {
    return new StorerData<Author>(
      this.author(),
      this.authorService,
      EntityMessages.edit('Autor', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/authors', this.author().id];
  }
}
