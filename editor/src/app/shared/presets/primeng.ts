import Lara from '@primeng/themes/lara';
import { definePreset, palette } from '@primeng/themes';

export const LaraBlue = definePreset(Lara, {
  semantic: {
    font: {
      family: '"Inter var", sans-serif',
    },
    primary: palette('{blue}'),
  },
});
