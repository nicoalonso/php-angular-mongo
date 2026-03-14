import { Directive } from '@angular/core';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractFormPage } from '@/shared/pages/abstract-form/abstract-form.page';
// Models
import { Entity, EntityPayload } from '@/shared/models/entity';
import { StorerData } from '@/shared/models/storer-data';

@Directive()
export abstract class AbstractEditPage<
  T extends Entity,
> extends AbstractFormPage {
  homeBreadcrumb: MenuItem = { icon: 'fas fa-home', routerLink: '/' };

  protected fillForm(entity: T): void {
    this.form.patchValue(entity);
  }

  protected persist(): void {
    if (!this.checkForm()) {
      this.toastService.topCenter().warn({
        summary: 'Existen errores en el formulario',
        detail: 'Revise los errores antes de continuar',
      });
      return;
    }

    const storer = this.getStoredData();
    const payload: EntityPayload = this.processForm(this.form.getRawValue());

    storer.service.updateItem(storer.entity.id, payload).subscribe({
      next: () => {
        this.toastService.topRight().success({
          summary: storer.messages.summary,
          detail: storer.messages.detail,
        });
        if (!storer.stayOnPage) {
          this.goBack();
        }
      },
      error: (error) => {
        console.error('Persist error', error);
        const { error: { message = '' } = {} } = error;
        this.toastService.topRight().error({
          summary: storer.messages.error,
          detail: message,
          life: 10_000,
        });
      },
    });
  }

  protected abstract getStoredData(): StorerData<T>;
}
