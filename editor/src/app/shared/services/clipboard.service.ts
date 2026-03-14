import { Injectable } from '@angular/core';

@Injectable()
export class ClipboardService {
  async copyText(text: string) {
    try {
      await navigator.clipboard.writeText(text);
    } catch (err) {
      console.error('Error al copiar: ', err);
    }
  }

  async copyBlob(text: string) {
    try {
      const type = 'text/plain';
      const blob = new Blob([text], { type });
      const data = [new ClipboardItem({ [type]: blob })];
      await navigator.clipboard.write(data);
    } catch (err) {
      console.error('Error al copiar: ', err);
    }
  }
}
