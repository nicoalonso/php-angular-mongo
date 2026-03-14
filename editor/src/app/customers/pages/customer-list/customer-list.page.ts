import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { Button } from 'primeng/button';
import { MenuItem } from 'primeng/api';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { CustomerService } from '@/customers/services/customer.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  customerAdapter,
  customerColumns,
  customerFormatter,
  customerStylable,
} from '@/customers/pages/customer-list/customer-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './customer-list.page.html',
  styleUrl: './customer-list.page.less',
})
export default class CustomerListPage extends AbstractListPage {
  private readonly customersPath = 'customers';
  protected readonly columns = customerColumns;
  protected readonly adapter = customerAdapter;
  protected readonly formatter = customerFormatter;
  protected readonly stylable = customerStylable;

  service = inject(CustomerService);

  breadcrumb: MenuItem[] = [
    { label: 'Clientes', styleClass: 'text-xl font-bold' },
  ];

  override getPath(): string {
    return this.customersPath;
  }
}
