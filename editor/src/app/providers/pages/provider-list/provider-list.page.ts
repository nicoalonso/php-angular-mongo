import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
// Framework
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractListPage } from '@/shared/pages/abstract-list/abstract-list.page';
// Services
import { ProviderService } from '@/providers/services/provider.service';
// Components
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
// Table
import {
  providerAdapter,
  providerColumns,
} from '@/providers/pages/provider-list/provider-table-item';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Button, FaIconComponent, ListTableComponent],
  templateUrl: './provider-list.page.html',
  styleUrl: './provider-list.page.less',
})
export default class ProviderListPage extends AbstractListPage {
  private readonly providersPath = 'providers';
  protected readonly columns = providerColumns;
  protected readonly adapter = providerAdapter;

  service = inject(ProviderService);

  breadcrumb = [{ label: 'Proveedores', styleClass: 'text-xl font-bold' }];

  override getPath(): string {
    return this.providersPath;
  }
}
