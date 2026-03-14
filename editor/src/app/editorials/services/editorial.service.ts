import { Injectable } from '@angular/core';
// Models
import { Editorial } from '@/editorials/model/editorial';
// Services
import { MutationService } from '@/shared/interfaces/mutation-service';

@Injectable({
  providedIn: 'root',
})
export class EditorialService extends MutationService<Editorial> {
  private readonly resource = 'editorials';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Editorial): Editorial {
    return Editorial.from(item);
  }
}
