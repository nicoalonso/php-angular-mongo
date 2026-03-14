export class BookDetail {
  constructor(
    public edition: string,
    public isbn: string,
    public language: string,
    public publishedAt: Date,
    public pages: number,
  ) {}

  public static from(item: BookDetail): BookDetail {
    return new BookDetail(
      item.edition,
      item.isbn,
      item.language,
      new Date(item.publishedAt),
      item.pages,
    );
  }
}
