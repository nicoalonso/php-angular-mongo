import { Injectable } from '@angular/core';
import { MutationService } from '@/shared/interfaces/mutation-service';
import { Sale } from '@/sales/model/sale';

@Injectable({
  providedIn: 'root',
})
export class SaleService extends MutationService<Sale> {
  private readonly resource = 'sales';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Sale): Sale {
    return Sale.from(item);
  }
}
