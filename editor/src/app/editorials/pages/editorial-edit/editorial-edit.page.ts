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
// Framework
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { TabsModule } from 'primeng/tabs';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { Divider } from 'primeng/divider';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Editorial } from '@/editorials/model/editorial';
import { StorerData } from '@/shared/models/storer-data';
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
    TabsModule,
    InputText,
    Message,
    Divider,
  ],
  templateUrl: './editorial-edit.page.html',
  styleUrl: './editorial-edit.page.less',
})
export default class EditorialEditPage
  extends AbstractEditPage<Editorial>
  implements OnInit
{
  private readonly webPattern =
    /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-]*)*(\?.*)?(#.*)?$/;

  private editorialService = inject(EditorialService);

  editorial = input.required<Editorial>();

  breadcrumb: MenuItem[] = [
    { label: 'Editoriales', routerLink: '/editorials' },
    { label: 'Editar Editorial', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  protected override makeForm() {
    this.form = new FormGroup({
      name: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      comercialName: new FormControl(''),
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
    this.fillForm(this.editorial());
  }

  onSave() {
    this.persist();
  }

  protected getStoredData(): StorerData<Editorial> {
    return new StorerData<Editorial>(
      this.editorial(),
      this.editorialService,
      EntityMessages.edit('Editorial', NounGenre.female),
    );
  }

  protected override getBackPath(): string[] {
    return ['/editorials', this.editorial().id];
  }
}
