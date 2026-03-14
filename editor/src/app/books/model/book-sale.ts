export class BookSale {
  constructor(
    public saleable: boolean,
    public price: number,
    public discount: number,
  ) {}

  public static from(item: BookSale): BookSale {
    return new BookSale(item.saleable, item.price, item.discount);
  }
}
