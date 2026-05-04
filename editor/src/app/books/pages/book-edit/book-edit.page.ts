import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
  model,
  OnInit,
} from '@angular/core';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
import dayjs from 'dayjs';
// Framework
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { InputText } from 'primeng/inputtext';
import { Message } from 'primeng/message';
import { MenuItem } from 'primeng/api';
import { InputGroup } from 'primeng/inputgroup';
import { InputGroupAddon } from 'primeng/inputgroupaddon';
import { DatePicker } from 'primeng/datepicker';
import { InputMask } from 'primeng/inputmask';
import { InputNumber } from 'primeng/inputnumber';
import { Textarea } from 'primeng/textarea';
import { ToggleSwitch } from 'primeng/toggleswitch';
import { Tooltip } from 'primeng/tooltip';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractEditPage } from '@/shared/pages/abstract-edit/abstract-edit.page';
// Models
import { Book } from '@/books/model/book';
import { Author } from '@/authors/model/author';
import { Editorial } from '@/editorials/model/editorial';
import { StorerData } from '@/shared/models/storer-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
import { EntityPayload } from '@/shared/models/entity';
// Services
import { BookService } from '@/books/services/book-service';
// Dialogs
import { AuthorDialogComponent } from '@/authors/dialogs/author-dialog/author-dialog.component';
import { EditorialDialogComponent } from '@/editorials/dialogs/editorial-dialog/editorial-dialog.component';
// Validators
import { emptyValidator } from '@/shared/validators/empty-validator';
import { priceValidator } from '@/books/validators/price-validator';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ReactiveFormsModule,
    Breadcrumb,
    Button,
    RouterLink,
    TabsModule,
    InputText,
    Message,
    FaIconComponent,
    InputGroup,
    InputGroupAddon,
    DatePicker,
    InputMask,
    InputNumber,
    Textarea,
    ToggleSwitch,
    Tooltip,
    AuthorDialogComponent,
    EditorialDialogComponent,
  ],
  templateUrl: './book-edit.page.html',
  styleUrl: './book-edit.page.less',
})
export default class BookEditPage
  extends AbstractEditPage<Book>
  implements OnInit
{
  private readonly isbnPattern: string = '^(97(8|9))-\\d{9}(\\d|X)$';
  public readonly maxDate = new Date();

  private bookService = inject(BookService);

  book = input.required<Book>();
  authorSelectorVisible = model(false);
  editorialSelectorVisible = model(false);

  breadcrumb: MenuItem[] = [
    { label: 'Libros', routerLink: '/books' },
    { label: 'Editar Libro', styleClass: 'text-xl font-bold' },
  ];

  constructor() {
    super();
  }

  override makeForm() {
    this.form = new FormGroup({
      title: new FormControl('', [emptyValidator, Validators.minLength(3)]),
      description: new FormControl(''),
      author: new FormGroup({
        id: new FormControl(null, [Validators.required]),
        name: new FormControl(null, [Validators.required]),
      }),
      editorial: new FormGroup({
        id: new FormControl(null, [Validators.required]),
        name: new FormControl(null, [Validators.required]),
      }),
      detail: new FormGroup({
        edition: new FormControl(''),
        isbn: new FormControl('', [
          emptyValidator,
          Validators.pattern(this.isbnPattern),
        ]),
        language: new FormControl(''),
        publishedAt: new FormControl(null, [Validators.required]),
        pages: new FormControl(null, [Validators.min(1)]),
      }),
      sale: new FormGroup({
        saleable: new FormControl(false),
        price: new FormControl(null, [Validators.min(0), priceValidator]),
        discount: new FormControl(null, [
          Validators.min(0),
          Validators.max(100),
        ]),
      }),
    });
  }

  ngOnInit() {
    this.fillForm(this.book());
  }

  onSave(): void {
    this.persist();
  }

  protected override processForm(data: object): EntityPayload {
    const { author, editorial, detail, ...rest } = data as Book;
    const { publishedAt, ...detailRest } = detail;

    return {
      ...rest,
      detail: {
        ...detailRest,
        publishedAt: dayjs(publishedAt).format('YYYY-MM-DD'),
      },
      authorId: author.id,
      editorialId: editorial.id,
    };
  }

  protected onAuthorKeydown(event: KeyboardEvent): void {
    if (event.key === '/') {
      event.preventDefault();
      this.authorSelectorVisible.set(true);
    } else if (event.key === 'Backspace' || event.key === 'Delete') {
      event.preventDefault();
      this.form.get('author')?.reset();
    }
  }

  protected onAuthorSelected(author: Author): void {
    this.form.get('author')?.patchValue({
      id: author.id,
      name: author.name,
    });
  }

  protected onEditorialKeydown(event: KeyboardEvent): void {
    if (event.key === '/') {
      event.preventDefault();
      this.editorialSelectorVisible.set(true);
    } else if (event.key === 'Backspace' || event.key === 'Delete') {
      event.preventDefault();
      this.form.get('editorial')?.reset();
    }
  }

  protected onEditorialSelected(editorial: Editorial): void {
    this.form.get('editorial')?.patchValue({
      id: editorial.id,
      name: editorial.name,
    });
  }

  protected getStoredData(): StorerData<Book> {
    return new StorerData<Book>(
      this.book(),
      this.bookService,
      EntityMessages.edit('Libro', NounGenre.male),
    );
  }

  protected override getBackPath(): string[] {
    return ['/books', this.book().id];
  }
}
