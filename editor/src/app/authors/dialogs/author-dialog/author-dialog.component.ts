import {
  ChangeDetectionStrategy,
  Component,
  effect,
  ElementRef,
  inject,
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
import { Author } from '@/authors/model/author';
// Services
import { AuthorService } from '@/authors/services/author.service';

@Component({
  selector: 'app-author-dialog',
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
  templateUrl: './author-dialog.component.html',
  styleUrl: './author-dialog.component.less',
})
export class AuthorDialogComponent {
  authorService = inject(AuthorService);

  visible = model.required<boolean>();
  selected = output<Author>();
  nameInput = viewChild<ElementRef<HTMLInputElement>>('nameInput');
  loading = signal(false);
  authors = signal<Author[]>([]);

  form: FormGroup;
  initialOpen = true;

  constructor() {
    this.form = new FormGroup({
      name: new FormControl(''),
      realName: new FormControl(''),
      nationality: new FormControl(''),
    });

    effect(() => {
      if (this.visible()) {
        setTimeout(() => {
          this.nameInput()?.nativeElement.select();
          this.nameInput()?.nativeElement.focus();
        }, 500);
        if (this.initialOpen) {
          this.initialOpen = false;
          this.onSearch();
        }
      }
    });
  }

  onSearch() {
    const options = new Map();
    const filters = this.form.getRawValue();

    for (const filtersKey in filters) {
      if (filters[filtersKey]) {
        options.set(filtersKey, filters[filtersKey]);
      }
    }
    if (options.size == 0) {
      options.set('sort', '+createdAt');
    }

    const query = Object.fromEntries(options);

    this.loading.set(true);
    this.authorService.search(query).subscribe((result) => {
      this.authors.set(result.items);
      this.loading.set(false);
    });
  }

  onClickItem(author: Author, $event: MouseEvent): void {
    $event.stopPropagation();
    this.selected.emit(author);
    this.visible.set(false);
  }
}
