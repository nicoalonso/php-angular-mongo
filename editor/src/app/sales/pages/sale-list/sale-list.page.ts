import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { SaleService } from '@/sales/services/sale.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  saleAdapter,
  saleColumns,
} from '@/sales/pages/sale-list/sale-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './sale-list.page.html',
  styleUrl: './sale-list.page.less',
})
export default class SaleListPage extends AbstractListPage {
  private readonly salesPath = 'sales';
  protected readonly columns = saleColumns;
  protected readonly adapter = saleAdapter;

  service = inject(SaleService);

  breadcrumb = [{ label: 'Ventas', styleClass: 'text-xl font-bold' }];

  override getPath(): string {
    return this.salesPath;
  }
}
