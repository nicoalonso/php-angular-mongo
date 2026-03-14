import { Entity } from '@/shared/models/entity';
import { AuthorDescriptor } from '@/authors/model/author-descriptor';
import { EditorialDescriptor } from '@/editorials/model/editorial-descriptor';
import { BookDetail } from '@/books/model/book-detail';
import { BookSale } from '@/books/model/book-sale';

export class Book extends Entity {
  constructor(
    id: string,
    public title: string,
    public description: string,
    public author: AuthorDescriptor,
    public editorial: EditorialDescriptor,
    public detail: BookDetail,
    public sale: BookSale,
    public stock: number,
  ) {
    super(id);
  }

  public static from(item: Book): Book {
    const book = new Book(
      item.id,
      item.title,
      item.description,
      AuthorDescriptor.from(item.author),
      EditorialDescriptor.from(item.editorial),
      BookDetail.from(item.detail),
      BookSale.from(item.sale),
      item.stock,
    );

    book.parse(item);
    return book;
  }
}
