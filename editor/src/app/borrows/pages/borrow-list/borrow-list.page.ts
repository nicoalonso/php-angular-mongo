import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { Button } from 'primeng/button';
import { Tooltip } from 'primeng/tooltip';
import { ConfirmDialog } from 'primeng/confirmdialog';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { BorrowService } from '@/borrows/services/borrow.service';
// Table
import {
  borrowAdapter,
  borrowColumns,
} from '@/borrows/pages/borrow-list/borrow-table-item';
import { ConfirmationService } from 'primeng/api';
import { ToastService } from '@/shared/services/toast.service';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    Button,
    FaIconComponent,
    ListTableComponent,
    Tooltip,
    ConfirmDialog,
  ],
  providers: [ConfirmationService],
  templateUrl: './borrow-list.page.html',
  styleUrl: './borrow-list.page.less',
})
export default class BorrowListPage extends AbstractListPage {
  private readonly borrowsPath = 'borrows';
  protected readonly columns = borrowColumns;
  protected readonly adapter = borrowAdapter;

  service = inject(BorrowService);
  protected confirmationService: ConfirmationService =
    inject(ConfirmationService);
  protected toastService: ToastService = inject(ToastService);

  breadcrumb = [{ label: 'Préstamos', styleClass: 'text-xl font-bold' }];

  onManualPenalty($event: Event): void {
    const message: string = `Se va a lanzar un proceso para calcular las penalizaciones de forma manual. Este proceso puede tardar unos segundos. ¿Deseas continuar?`;

    this.confirmationService.confirm({
      target: $event.target as EventTarget,
      message: message,
      header: 'Calcular penalización manualmente',
      icon: 'fas fa-ticket',
      acceptButtonStyleClass: 'p-button-danger p-button-text',
      rejectButtonStyleClass: 'p-button-text p-button-text',
      acceptIcon: 'fas fa-hands mr-2',
      rejectIcon: '',
      acceptLabel: ' Lanzar proceso',
      rejectLabel: 'Cancelar',
      accept: () => {
        this.manualPenalty();
      },
    });
  }

  private manualPenalty(): void {
    this.service.manualPenalty().subscribe({
      next: () => {
        this.toastService.topRight().success({
          summary: 'Proceso lanzado',
          detail:
            'El proceso de cálculo de penalizaciones se ha lanzado correctamente.',
        });
      },
      error: (e) => {
        const errorMessage = e.error?.message || e.message;
        console.error(errorMessage);

        this.toastService.topRight().error({
          summary: 'Error al lanzar el proceso',
          detail: `Se ha producido un error al lanzar el proceso": ${errorMessage}`,
          life: 10000,
        });
      },
    });
  }

  override getPath(): string {
    return this.borrowsPath;
  }
}
