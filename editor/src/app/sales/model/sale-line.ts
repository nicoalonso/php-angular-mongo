import { BookDescriptor } from '@/books/model/book-descriptor';

export class SaleLine {
  constructor(
    public lineId: string,
    public book: BookDescriptor,
    public quantity: number,
    public price: number,
    public discount: number,
    public total: number,
  ) {}

  public static empty(): SaleLine {
    return new SaleLine('', BookDescriptor.empty(), 1, 0, 0, 0);
  }

  public static from(item: SaleLine): SaleLine {
    return new SaleLine(
      item.lineId,
      BookDescriptor.from(item.book),
      item.quantity,
      item.price,
      item.discount,
      item.total,
    );
  }
}
