import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'defaultValue',
  standalone: true,
})
export class DefaultValuePipe implements PipeTransform {
  private readonly DEFAULT_VALUE: string = '--';

  transform(value: unknown, ...args: unknown[]): unknown {
    if (!value) {
      let defaultValue = this.DEFAULT_VALUE;
      if (args.length > 0 && typeof args[0] === 'string') {
        defaultValue = args[0];
      }
      return defaultValue;
    }

    return value;
  }
}
