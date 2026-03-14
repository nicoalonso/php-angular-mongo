import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { Button } from 'primeng/button';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { PurchaseService } from '@/purchase/services/purchase.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  purchaseAdapter,
  purchaseColumns,
} from '@/purchase/pages/purchase-list/purchase-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './purchase-list.page.html',
  styleUrl: './purchase-list.page.less',
})
export default class PurchaseListPage extends AbstractListPage {
  private readonly purchasesPath = 'purchases';
  protected readonly columns = purchaseColumns;
  protected readonly adapter = purchaseAdapter;

  service = inject(PurchaseService);

  breadcrumb = [{ label: 'Entradas', styleClass: 'text-xl font-bold' }];

  override getPath(): string {
    return this.purchasesPath;
  }
}
