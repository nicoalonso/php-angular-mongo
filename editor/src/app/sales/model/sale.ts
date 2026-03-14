import { Entity } from '@/shared/models/entity';
import { CustomerDescriptor } from '@/customers/model/customer-descriptor';
import { SaleInvoice } from '@/sales/model/sale-invoice';
import { SaleLine } from '@/sales/model/sale-line';

export class Sale extends Entity {
  public lines: SaleLine[];

  constructor(
    id: string,
    public customer: CustomerDescriptor,
    public number: string,
    public invoice: SaleInvoice,
  ) {
    super(id);
    this.lines = [];
  }

  public static from(item: Sale): Sale {
    const sale = new Sale(
      item.id,
      CustomerDescriptor.from(item.customer),
      item.number,
      SaleInvoice.from(item.invoice),
    );

    if (item.lines) {
      sale.lines = item.lines.map((line) => SaleLine.from(line));
    }

    sale.parse(item);
    return sale;
  }
}
