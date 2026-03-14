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
import { Book } from '@/books/model/book';
// Services
import { BookService } from '@/books/services/book-service';

@Component({
  selector: 'app-book-dialog',
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
  templateUrl: './book-dialog.component.html',
  styleUrl: './book-dialog.component.less',
})
export class BookDialogComponent {
  bookService = inject(BookService);

  visible = model.required<boolean>();
  saleable = input<boolean>(false);
  selected = output<Book>();
  titleInput = viewChild<ElementRef<HTMLInputElement>>('titleInput');
  loading = signal(false);
  books = signal<Book[]>([]);

  form: FormGroup;
  initialOpen = true;

  constructor() {
    this.form = new FormGroup({
      title: new FormControl(''),
      author: new FormControl(''),
      editorial: new FormControl(''),
      isbn: new FormControl(''),
    });

    effect(() => {
      if (this.visible()) {
        setTimeout(() => {
          this.titleInput()?.nativeElement.select();
          this.titleInput()?.nativeElement.focus();
        }, 500);
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

    if (this.saleable()) {
      options.set('saleable', true);
    }

    if (options.size == 0) {
      options.set('sort', '+createdAt');
    }

    const query = Object.fromEntries(options);
    this.loading.set(true);
    this.bookService.search(query).subscribe((result) => {
      this.books.set(result.items);
      this.loading.set(false);
    });
  }

  onClickItem(book: Book, $event: MouseEvent): void {
    $event.stopPropagation();
    this.selected.emit(book);
    this.visible.set(false);
  }
}
