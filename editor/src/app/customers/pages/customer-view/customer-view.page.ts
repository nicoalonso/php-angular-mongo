import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe, NgClass } from '@angular/common';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { ConfirmationService, MenuItem } from 'primeng/api';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Divider } from 'primeng/divider';
import { TableModule } from 'primeng/table';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Model
import { Customer } from '@/customers/model/customer';
import { Borrow } from '@/borrows/model/borrow';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { CustomerService } from '@/customers/services/customer.service';
// Components
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
// Pipes
import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';
import { DefaultValuePipe } from '@/shared/pipes/default-value.pipe';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ConfirmDialog,
    Breadcrumb,
    DateAgoPipe,
    Button,
    RouterLink,
    TabsModule,
    TrackingSectionComponent,
    Divider,
    FaIconComponent,
    DefaultValuePipe,
    BtnCopyComponent,
    DatePipe,
    TableModule,
    CurrencyPipe,
    DecimalPipe,
    NgClass,
  ],
  providers: [ConfirmationService],
  templateUrl: './customer-view.page.html',
  styleUrl: './customer-view.page.less',
})
export default class CustomerViewPage extends AbstractViewDeletePage<Customer> {
  private customerService = inject(CustomerService);

  customer = input.required<Customer>();
  borrows = input.required<Borrow[]>();

  breadcrumb: MenuItem[] = [
    { label: 'Clientes', routerLink: '/customers' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Customer> {
    return new EraserData<Customer>(
      this.customer(),
      this.customer().name,
      this.customerService,
      EntityMessages.delete('Cliente', NounGenre.male),
    );
  }

  override getPathBack(): string[] {
    return ['/customers'];
  }
}
