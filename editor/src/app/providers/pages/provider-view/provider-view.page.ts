import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { Divider } from 'primeng/divider';
import { ConfirmationService, MenuItem } from 'primeng/api';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Models
import { Provider } from '@/providers/model/provider';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { ProviderService } from '@/providers/services/provider.service';
// Components
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
// Pipes
import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';
import { DefaultValuePipe } from '@/shared/pipes/default-value.pipe';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    ConfirmDialog,
    Breadcrumb,
    Button,
    DateAgoPipe,
    RouterLink,
    TabsModule,
    BtnCopyComponent,
    DefaultValuePipe,
    Divider,
    FaIconComponent,
    TrackingSectionComponent,
  ],
  providers: [ConfirmationService],
  templateUrl: './provider-view.page.html',
  styleUrl: './provider-view.page.less',
})
export default class ProviderViewPage extends AbstractViewDeletePage<Provider> {
  private providerService = inject(ProviderService);

  provider = input.required<Provider>();

  breadcrumb: MenuItem[] = [
    { label: 'Proveedores', routerLink: '/providers' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Provider> {
    return new EraserData<Provider>(
      this.provider(),
      this.provider().name,
      this.providerService,
      EntityMessages.delete('Proveedor', NounGenre.male),
    );
  }

  override getPathBack(): string[] {
    return ['/providers'];
  }
}
