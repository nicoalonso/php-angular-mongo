import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export const emptyValidator: ValidatorFn = (
  control: AbstractControl,
): ValidationErrors | null => {
  const value = control.value as string;
  return value && value.trim() ? null : { empty: true };
};
