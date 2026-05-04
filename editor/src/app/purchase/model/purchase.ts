import { Entity } from '@/shared/models/entity';
import { ProviderDescriptor } from '@/providers/model/provider-descriptor';
import { PurchaseInvoice } from './purchase-invoice';
import { PurchaseLine } from '@/purchase/model/purchase-line';

export class Purchase extends Entity {
  public lines: PurchaseLine[];

  constructor(
    id: string,
    public provider: ProviderDescriptor,
    public purchasedAt: Date,
    public invoice: PurchaseInvoice,
  ) {
    super(id);
    this.lines = [];
  }

  public static from(item: Purchase): Purchase {
    const purchase = new Purchase(
      item.id,
      ProviderDescriptor.from(item.provider),
      new Date(item.purchasedAt),
      PurchaseInvoice.from(item.invoice),
    );

    if (item.lines) {
      purchase.lines = item.lines.map((line) => PurchaseLine.from(line));
    }

    purchase.parse(item);
    return purchase;
  }
}
