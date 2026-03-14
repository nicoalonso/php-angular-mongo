import {
  AbstractControl,
  FormArray,
  ValidationErrors,
  ValidatorFn,
} from '@angular/forms';

export const emptyLinesValidator: ValidatorFn = (
  control: AbstractControl,
): ValidationErrors | null => {
  if (!(control instanceof FormArray)) {
    return null;
  }

  return control.length === 0 ? { empty: 'The lines must not be empty' } : null;
};
