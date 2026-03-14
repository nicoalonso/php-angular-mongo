import {
  ChangeDetectionStrategy,
  Component,
  computed,
  input,
} from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe, NgClass } from '@angular/common';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { MenuItem, PrimeTemplate } from 'primeng/api';
import { TabsModule } from 'primeng/tabs';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { Divider } from 'primeng/divider';
// Pages
import { AbstractViewPage } from '@/shared/pages/abstract-view/abstract-view.page';
// Models
import { Borrow } from '@/borrows/model/borrow';
// Components
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
// Pipes
import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';
import { TableModule } from 'primeng/table';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    Breadcrumb,
    Button,
    DateAgoPipe,
    RouterLink,
    FaIconComponent,
    TabsModule,
    TrackingSectionComponent,
    BtnCopyComponent,
    DatePipe,
    Divider,
    NgClass,
    PrimeTemplate,
    TableModule,
    DecimalPipe,
    CurrencyPipe,
  ],
  templateUrl: './borrow-view.page.html',
  styleUrl: './borrow-view.page.less',
})
export default class BorrowViewPage extends AbstractViewPage {
  private readonly today = new Date();

  borrow = input.required<Borrow>();
  isDue = computed(
    () => !this.borrow().returned && this.borrow().dueDate < this.today,
  );

  breadcrumb: MenuItem[] = [
    { label: 'Préstamos', routerLink: '/borrows' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getPathBack(): string[] {
    return ['/borrows'];
  }
}
