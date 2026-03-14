export class SaleInvoice {
  constructor(
    public date: Date,
    public amount: number,
    public taxPercentage: number,
    public taxes: number,
    public total: number,
  ) {}

  public static from(item: SaleInvoice): SaleInvoice {
    return new SaleInvoice(
      new Date(item.date),
      item.amount,
      item.taxPercentage,
      item.taxes,
      item.total,
    );
  }
}
