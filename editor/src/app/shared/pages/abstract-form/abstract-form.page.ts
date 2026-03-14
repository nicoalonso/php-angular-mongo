import { Directive, inject } from '@angular/core';
import {
  AbstractControl,
  FormArray,
  FormControl,
  FormGroup,
} from '@angular/forms';
import { Router } from '@angular/router';
import { EntityPayload } from '@/shared/models/entity';
import { ToastService } from '@/shared/services/toast.service';

@Directive()
export abstract class AbstractFormPage {
  protected toastService = inject(ToastService);
  protected router = inject(Router);

  form!: FormGroup;

  protected constructor() {
    this.makeForm();
  }

  protected makeForm(): void {
    this.form = new FormGroup({});
  }

  protected checkForm(): boolean {
    this.form.markAllAsTouched();
    this.markGroupDirty(this.form);
    return this.form.valid;
  }

  private markGroupDirty(group: FormGroup): void {
    Object.keys(group.controls).forEach((key) => {
      const control = group.get(key);
      control?.markAsDirty();

      if (control instanceof FormGroup) {
        this.markGroupDirty(control);
      }

      if (control instanceof FormArray) {
        control.controls.forEach((group) => {
          this.markGroupDirty(group as FormGroup);
        });
      }
    });
  }

  protected processForm(data: object): EntityPayload {
    return data as EntityPayload;
  }

  hasError(field: string, type: string): boolean {
    const control = this.form.get(field);
    if (!control) {
      return false;
    }

    let dirty = true;
    if (!(control instanceof FormArray)) {
      dirty = control.dirty;
    } else {
      for (const group of control.controls) {
        for (const key of Object.keys((group as FormGroup).controls)) {
          if (!(group as FormGroup).get(key)?.dirty) {
            dirty = false;
            break;
          }
        }
      }
    }

    return control.touched && dirty && control.errors && control.errors[type];
  }

  hasArrayError(
    form: FormArray,
    index: number,
    field: string,
    type: string,
  ): boolean {
    const control = form.at(index).get(field);
    if (!control) {
      return false;
    }

    return (
      control.touched && control.dirty && control.errors && control.errors[type]
    );
  }

  protected formDirty(item: AbstractControl | null) {
    if (!item) {
      return;
    }

    if (item instanceof FormControl) {
      item.markAsDirty();
      return;
    }

    if (item instanceof FormGroup || item instanceof FormArray) {
      if (item instanceof FormArray) {
        item.markAsDirty();
      }
      Object.keys(item.controls).forEach((key) => {
        this.formDirty(item.get(key));
      });
      return;
    }
  }

  goBack(): void {
    this.router.navigate(this.getBackPath()).then();
  }

  protected getBackPath(): string[] {
    return ['/'];
  }
}
