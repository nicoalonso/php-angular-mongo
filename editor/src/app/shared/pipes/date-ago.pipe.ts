import { Pipe, PipeTransform } from '@angular/core';

type intervalType = {
  value: number;
  singular: string;
  plural: string;
};

@Pipe({
  name: 'dateAgo',
  standalone: true,
})
export class DateAgoPipe implements PipeTransform {
  private readonly intervals: intervalType[] = [
    { value: 31536000, singular: 'año', plural: 'años' },
    { value: 2592000, singular: 'mes', plural: 'meses' },
    { value: 604800, singular: 'semana', plural: 'semanas' },
    { value: 86400, singular: 'día', plural: 'días' },
    { value: 3600, singular: 'hora', plural: 'horas' },
    { value: 60, singular: 'minuto', plural: 'minutos' },
    { value: 1, singular: 'segundo', plural: 'segundos' },
  ];

  transform(value: Date | undefined): string {
    if (!value) {
      return '';
    }

    const seconds = Math.floor((+new Date() - +new Date(value)) / 1000);
    if (seconds < 29) {
      return 'ahora mismo';
    }

    let title = 'no se sabe cuando';
    for (const interval of this.intervals) {
      const counter = Math.floor(seconds / interval.value);
      if (counter > 0) {
        if (counter === 1) {
          title = `hace ${counter} ${interval.singular}`;
        } else {
          title = `hace ${counter} ${interval.plural}`;
        }
        break;
      }
    }

    return title;
  }
}
