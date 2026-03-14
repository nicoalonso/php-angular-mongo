import { computed, Directive, signal } from '@angular/core';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractFormPage } from '@/shared/pages/abstract-form/abstract-form.page';
// Models
import { Entity, EntityPayload } from '@/shared/models/entity';
import { CreatorData } from '@/shared/models/creator-data';
import { StepPageControl } from '@/shared/models/step-page';
import dayjs from 'dayjs';

@Directive()
export abstract class AbstractCreatePage<
  T extends Entity,
> extends AbstractFormPage {
  homeBreadcrumb: MenuItem = { icon: 'fas fa-home', routerLink: '/' };

  protected steps: StepPageControl;

  activeStep = signal<number>(0);
  detailBadges = computed<string[]>(() =>
    this.steps.computeBadges(this.activeStep()),
  );

  protected constructor() {
    super();

    this.steps = new StepPageControl();
  }

  protected persist(): void {
    if (!this.checkForm()) {
      return;
    }

    const creator = this.getCreatedData();
    const payload: EntityPayload = this.processForm(this.form.getRawValue());

    creator.service.createItem(payload).subscribe({
      next: () => {
        this.toastService.topRight().success({
          summary: creator.messages.summary,
          detail: creator.messages.detail,
        });
        this.goBack();
      },
      error: (error) => {
        console.error('Error create', error);
        const { error: { message = '' } = {} } = error;

        this.toastService.topRight().error({
          summary: creator.messages.error,
          detail: message,
          life: 10000,
        });
      },
    });
  }

  protected override checkForm(): boolean {
    const isValid = super.checkForm();
    if (!isValid && this.steps.count > 0) {
      for (let i = 0; i < this.steps.count; i++) {
        if (!this.checkStep(i)) {
          this.activeStep.set(i);
          break;
        }
      }
    }

    return isValid;
  }

  onNextStep(): void {
    if (!this.checkStep(this.activeStep())) {
      this.toastService.topCenter().warn({
        summary: 'Existen errores en el formulario',
        detail: 'Revise los errores antes de continuar',
      });
      return;
    }

    this.calcBadges();

    this.activeStep.update((value) =>
      Math.min(value + 1, this.steps.count - 1),
    );
  }

  private calcBadges(): void {
    const stepControl = this.steps.find(this.activeStep());
    if (stepControl && stepControl.hasBadge) {
      const values = [];
      for (const controlName of stepControl.badge) {
        let value = this.form.get(controlName)?.value;
        if (value instanceof Date) {
          value = dayjs(value).format('DD/MM/YYYY');
        }
        values.push(value);
      }
      stepControl.setValues(values);
    }
  }

  onPreviousStep(): void {
    this.activeStep.update((value) => Math.max(value - 1, 0));
  }

  protected checkStep(step: number): boolean {
    const stepControl = this.steps.find(step);
    if (!stepControl) {
      return true;
    }

    let isValid = true;
    for (const controlName of stepControl.validate) {
      const control = this.form.get(controlName);
      control?.markAsTouched();
      control?.markAsDirty();
      isValid = isValid && !!control && control.valid;
    }

    return isValid;
  }

  protected addStep(
    label: string,
    validate: string[] = [],
    badge: string[] = [],
  ): void {
    this.steps.add(label, validate, badge);
  }

  protected abstract getCreatedData(): CreatorData<T>;
}
