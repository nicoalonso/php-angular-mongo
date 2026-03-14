import { Directive, inject } from '@angular/core';
import { ConfirmationService } from 'primeng/api';
// Pages
import { AbstractViewPage } from '@/shared/pages/abstract-view/abstract-view.page';
// Models
import { Entity } from '@/shared/models/entity';
// Services
import { ToastService } from '@/shared/services/toast.service';
import { EraserData } from '@/shared/models/eraser-data';

@Directive()
export abstract class AbstractViewDeletePage<
  T extends Entity,
> extends AbstractViewPage {
  protected confirmationService: ConfirmationService =
    inject(ConfirmationService);
  protected toastService: ToastService = inject(ToastService);

  onDelete($event: Event) {
    const eraser = this.getEraserData();
    const message: string = `Vas a eliminar "${eraser.name}" para todos los usuarios.`;

    this.confirmationService.confirm({
      target: $event.target as EventTarget,
      message: message,
      header: eraser.messages.title,
      icon: 'fas fa-trash',
      acceptButtonStyleClass: 'p-button-danger p-button-text',
      rejectButtonStyleClass: 'p-button-text p-button-text',
      acceptIcon: 'fas fa-trash mr-2',
      rejectIcon: '',
      acceptLabel: ' Borrar',
      rejectLabel: 'Cancelar',
      accept: () => {
        this.deleteItem(eraser);
      },
    });
  }

  protected deleteItem(eraser: EraserData<T>): void {
    eraser.service.removeItem(eraser.entity).subscribe({
      next: () => {
        this.toastService.topRight().info({
          summary: eraser.messages.summary,
          detail: eraser.getDetail(),
        });
        this.goBack();
      },
      error: (e) => {
        const errorMessage = e.error?.message || e.message;
        console.error(errorMessage);

        this.toastService.topRight().error({
          summary: eraser.messages.error,
          detail: `No se ha podido borrar "${eraser.name}": ${errorMessage}`,
          life: 10000,
        });
      },
    });
  }

  abstract getEraserData(): EraserData<T>;
}
