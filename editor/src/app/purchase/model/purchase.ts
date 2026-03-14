import { Entity } from '@/shared/models/entity';
import { ProviderDescriptor } from '@/providers/model/provider-descriptor';
import { PurchaseInventory } from '@/purchase/model/purchase-inventory';
import { PurchaseLine } from '@/purchase/model/purchase-line';

export class Purchase extends Entity {
  public lines: PurchaseLine[];

  constructor(
    id: string,
    public provider: ProviderDescriptor,
    public purchasedAt: Date,
    public invoice: PurchaseInventory,
  ) {
    super(id);
    this.lines = [];
  }

  public static from(item: Purchase): Purchase {
    const purchase = new Purchase(
      item.id,
      ProviderDescriptor.from(item.provider),
      new Date(item.purchasedAt),
      PurchaseInventory.from(item.invoice),
    );

    if (item.lines) {
      purchase.lines = item.lines.map((line) => PurchaseLine.from(line));
    }

    purchase.parse(item);
    return purchase;
  }
}
