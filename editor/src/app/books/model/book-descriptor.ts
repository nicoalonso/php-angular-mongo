export class BookDescriptor {
  constructor(
    public id: string,
    public title: string,
    public isbn: string,
  ) {}

  public static empty(): BookDescriptor {
    return new BookDescriptor('', '', '');
  }

  public static from(item: BookDescriptor): BookDescriptor {
    return new BookDescriptor(item.id, item.title, item.isbn);
  }
}
