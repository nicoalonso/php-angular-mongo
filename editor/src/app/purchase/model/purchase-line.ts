import { BookDescriptor } from '@/books/model/book-descriptor';

export class PurchaseLine {
  constructor(
    public lineId: string,
    public book: BookDescriptor,
    public quantity: number,
    public unitPrice: number,
    public discountPercentage: number,
    public total: number,
  ) {}

  public static empty(): PurchaseLine {
    return new PurchaseLine('', BookDescriptor.empty(), 0, 0, 0, 0);
  }

  public static from(item: PurchaseLine): PurchaseLine {
    return new PurchaseLine(
      item.lineId,
      BookDescriptor.from(item.book),
      item.quantity,
      item.unitPrice,
      item.discountPercentage,
      item.total,
    );
  }
}
