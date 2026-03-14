import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe } from '@angular/common';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { RouterLink } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { ConfirmationService, MenuItem } from 'primeng/api';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Models
import { Purchase } from '@/purchase/model/purchase';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { PurchaseService } from '@/purchase/services/purchase.service';
// Components
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
// Pipes
import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';
import { Divider } from 'primeng/divider';
import { TableModule } from 'primeng/table';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ConfirmDialog,
    Breadcrumb,
    Button,
    DateAgoPipe,
    RouterLink,
    TabsModule,
    FaIconComponent,
    TrackingSectionComponent,
    BtnCopyComponent,
    DatePipe,
    CurrencyPipe,
    DecimalPipe,
    Divider,
    TableModule,
  ],
  providers: [ConfirmationService],
  templateUrl: './purchase-view.page.html',
  styleUrl: './purchase-view.page.less',
})
export default class PurchaseViewPage extends AbstractViewDeletePage<Purchase> {
  private purchaseService = inject(PurchaseService);

  purchase = input.required<Purchase>();

  breadcrumb: MenuItem[] = [
    { label: 'Entradas', routerLink: '/purchases' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Purchase> {
    return new EraserData<Purchase>(
      this.purchase(),
      this.purchase().invoice.number,
      this.purchaseService,
      EntityMessages.delete('Entrada', NounGenre.female),
    );
  }

  override getPathBack(): string[] {
    return ['/purchases'];
  }
}
