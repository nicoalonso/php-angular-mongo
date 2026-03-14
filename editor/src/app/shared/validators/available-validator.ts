import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export const availableValidator: ValidatorFn = (
  control: AbstractControl,
): ValidationErrors | null => {
  const value = control.value as boolean | false;
  return value ? null : { unavailable: true };
};
