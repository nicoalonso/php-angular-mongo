import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { AuthorService } from '@/authors/services/author.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  authorAdapter,
  authorColumns,
} from '@/authors/pages/author-list/author-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './author-list.page.html',
  styleUrl: './author-list.page.less',
})
export default class AuthorListPage extends AbstractListPage {
  private readonly authorsPath = 'authors';
  protected readonly columns = authorColumns;
  protected readonly adapter = authorAdapter;

  service = inject(AuthorService);

  breadcrumb: MenuItem[] = [
    { label: 'Autores', styleClass: 'text-xl font-bold' },
  ];

  override getPath(): string {
    return this.authorsPath;
  }
}
