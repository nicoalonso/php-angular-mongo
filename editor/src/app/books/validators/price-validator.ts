import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export const priceValidator: ValidatorFn = (
  control: AbstractControl,
): ValidationErrors | null => {
  const value = control.value as number;
  const saleable = control.parent?.get('saleable')?.value as boolean;
  return !saleable || (value !== null && value > 0)
    ? null
    : { priceRequired: true };
};
