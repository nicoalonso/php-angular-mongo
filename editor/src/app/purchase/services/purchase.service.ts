import { Injectable } from '@angular/core';
import { MutationService } from '@/shared/interfaces/mutation-service';
import { Purchase } from '@/purchase/model/purchase';

@Injectable({
  providedIn: 'root',
})
export class PurchaseService extends MutationService<Purchase> {
  private readonly resource = 'purchases';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Purchase): Purchase {
    return Purchase.from(item);
  }
}
