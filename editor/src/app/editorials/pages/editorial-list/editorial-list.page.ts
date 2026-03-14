import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { Button } from 'primeng/button';
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { EditorialService } from '@/editorials/services/editorial.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  editorialAdapter,
  editorialColumns,
} from '@/editorials/pages/editorial-list/editorial-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './editorial-list.page.html',
  styleUrl: './editorial-list.page.less',
})
export default class EditorialListPage extends AbstractListPage {
  private readonly editorialsPath = 'editorials';
  protected readonly columns = editorialColumns;
  protected readonly adapter = editorialAdapter;

  service = inject(EditorialService);

  breadcrumb: MenuItem[] = [
    { label: 'Editoriales', styleClass: 'text-xl font-bold' },
  ];

  override getPath(): string {
    return this.editorialsPath;
  }
}
