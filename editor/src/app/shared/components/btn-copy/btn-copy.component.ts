import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { Button } from 'primeng/button';
import { ClipboardService } from '@/shared/services/clipboard.service';
import { ToastService } from '@/shared/services/toast.service';

@Component({
  selector: 'app-btn-copy',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button],
  providers: [ClipboardService],
  templateUrl: './btn-copy.component.html',
  styleUrl: './btn-copy.component.less',
})
export class BtnCopyComponent {
  private clipboardService: ClipboardService = inject(ClipboardService);
  private toastService: ToastService = inject(ToastService);

  title = input<string>('');
  text = input<string | undefined>('');

  copy(): void {
    if (!this.text()) {
      return;
    }

    this.clipboardService.copyText(this.text()!).then(() => {
      this.toastService.topRight().info({
        summary: 'Copiado!',
        detail: this.title() + ' copiado al portapapeles',
      });
    });
  }
}
