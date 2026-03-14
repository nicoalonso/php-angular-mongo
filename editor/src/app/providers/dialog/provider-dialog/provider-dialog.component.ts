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
import { Provider } from '@/providers/model/provider';
// Services
import { ProviderService } from '@/providers/services/provider.service';

@Component({
  selector: 'app-provider-dialog',
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
  templateUrl: './provider-dialog.component.html',
  styleUrl: './provider-dialog.component.less',
})
export class ProviderDialogComponent {
  providerService = inject(ProviderService);

  visible = model.required<boolean>();
  selected = output<Provider>();
  nameInput = viewChild<ElementRef<HTMLInputElement>>('nameInput');
  loading = signal(false);
  providers = signal<Provider[]>([]);

  form: FormGroup;
  initialOpen = true;

  constructor() {
    this.form = new FormGroup({
      name: new FormControl(''),
      comercialName: new FormControl(''),
      website: new FormControl(''),
    });

    effect(() => {
      if (this.visible()) {
        setTimeout(() => {
          this.nameInput()?.nativeElement.select();
          this.nameInput()?.nativeElement.focus();
        }, 500);
      }
      if (this.initialOpen) {
        this.initialOpen = false;
        this.onSearch();
      }
    });
  }

  onSearch() {
    const filters = this.form.getRawValue();
    const options = new Map<unknown, unknown>(
      Object.entries(filters).filter(([, value]) => value),
    );

    if (options.size == 0) {
      options.set('sort', '+createdAt');
    }

    const query = Object.fromEntries(options);
    this.loading.set(true);
    this.providerService.search(query).subscribe((result) => {
      this.providers.set(result.items);
      this.loading.set(false);
    });
  }

  onClickItem(provider: Provider, $event: MouseEvent): void {
    $event.stopPropagation();
    this.selected.emit(provider);
    this.visible.set(false);
  }
}
