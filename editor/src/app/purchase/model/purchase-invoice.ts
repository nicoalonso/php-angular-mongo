export class PurchaseInvoice {
  constructor(
    public number: string,
    public amount: number,
    public taxes: number,
    public total: number,
  ) {}

  public static from(item: PurchaseInvoice): PurchaseInvoice {
    return new PurchaseInvoice(
      item.number,
      item.amount,
      item.taxes,
      item.total,
    );
  }
}
