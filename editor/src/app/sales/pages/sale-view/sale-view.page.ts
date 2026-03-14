import { ChangeDetectionStrategy, Component, input } from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe } from '@angular/common';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem, PrimeTemplate } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { Divider } from 'primeng/divider';
import { TableModule } from 'primeng/table';
// Pages
import { AbstractViewPage } from '@/shared/pages/abstract-view/abstract-view.page';
// Models
import { Sale } from '@/sales/model/sale';
// Components
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
// Pipes
import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    Breadcrumb,
    Button,
    DateAgoPipe,
    RouterLink,
    TabsModule,
    FaIconComponent,
    BtnCopyComponent,
    DatePipe,
    CurrencyPipe,
    DecimalPipe,
    Divider,
    TrackingSectionComponent,
    PrimeTemplate,
    TableModule,
  ],
  templateUrl: './sale-view.page.html',
  styleUrl: './sale-view.page.less',
})
export default class SaleViewPage extends AbstractViewPage {
  sale = input.required<Sale>();

  breadcrumb: MenuItem[] = [
    { label: 'Ventas', routerLink: '/sales' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getPathBack(): string[] {
    return ['/sales'];
  }
}
