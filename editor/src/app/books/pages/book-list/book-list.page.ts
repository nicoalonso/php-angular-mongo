import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem } from 'primeng/api';
import { Button } from 'primeng/button';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { BookService } from '@/books/services/book-service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  bookAdapter,
  bookColumns,
} from '@/books/pages/book-list/book-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './book-list.page.html',
  styleUrl: './book-list.page.less',
})
export default class BookListPage extends AbstractListPage {
  private readonly booksPath = 'books';
  protected readonly columns = bookColumns;
  protected readonly adapter = bookAdapter;

  service = inject(BookService);

  breadcrumb: MenuItem[] = [
    { label: 'Libros', styleClass: 'text-xl font-bold' },
  ];

  override getPath(): string {
    return this.booksPath;
  }
}
