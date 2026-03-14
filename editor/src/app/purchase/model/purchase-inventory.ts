export class PurchaseInventory {
  constructor(
    public number: string,
    public amount: number,
    public taxes: number,
    public total: number,
  ) {}

  public static from(item: PurchaseInventory): PurchaseInventory {
    return new PurchaseInventory(
      item.number,
      item.amount,
      item.taxes,
      item.total,
    );
  }
}
