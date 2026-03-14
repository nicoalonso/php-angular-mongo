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
import { Editorial } from '@/editorials/model/editorial';
// Services
import { EditorialService } from '@/editorials/services/editorial.service';

@Component({
  selector: 'app-editorial-dialog',
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
  templateUrl: './editorial-dialog.component.html',
  styleUrl: './editorial-dialog.component.less',
})
export class EditorialDialogComponent {
  editorialService = inject(EditorialService);

  visible = model.required<boolean>();
  selected = output<Editorial>();

  nameInput = viewChild<ElementRef<HTMLInputElement>>('nameInput');
  loading = signal(false);
  editorials = signal<Editorial[]>([]);

  form: FormGroup;
  initialOpen = true;

  constructor() {
    this.form = new FormGroup({
      name: new FormControl(''),
      comercialName: new FormControl(''),
    });

    effect(() => {
      if (this.visible()) {
        setTimeout(() => {
          this.nameInput()?.nativeElement.focus();
          this.nameInput()?.nativeElement.select();
        }, 500);

        if (this.initialOpen) {
          this.initialOpen = false;
          this.onSearch();
        }
      }
    });
  }

  onSearch() {
    const filters = this.form.getRawValue();
    const options = new Map();

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
    this.editorialService.search(query).subscribe((result) => {
      this.editorials.set(result.items);
      this.loading.set(false);
    });
  }

  onClickItem(editorial: Editorial, $event: MouseEvent): void {
    $event.stopPropagation();
    this.selected.emit(editorial);
    this.visible.set(false);
  }
}
