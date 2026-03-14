import { ListColumn } from '@/shared/models/list-column';
import { Book } from '@/books/model/book';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { shortText } from '@/shared/utils/text-utils';

export const bookColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('title', 'Título'),
  ListColumn.text('author', 'Autor'),
  ListColumn.text('editorial', 'Editorial'),
  ListColumn.text('isbn', 'ISBN'),
  ListColumn.date('publishedAt', 'Publicado en'),
];

interface BookTableItem extends TableIdentifier {
  id: string;
  title: string;
  author: string;
  editorial: string;
  isbn: string;
  publishedAt: Date;
}

const bookTableAdapter: AdapterFnc<Book, BookTableItem> = (
  value: Book,
): BookTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  title: value.title,
  author: value.author.name,
  editorial: value.editorial.name,
  isbn: value.detail.isbn,
  publishedAt: value.detail.publishedAt,
});

export const bookAdapter = bookTableAdapter as AdapterFnc;
