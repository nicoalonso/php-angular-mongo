import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { RouterLink } from '@angular/router';
// Framework
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { ConfirmationService, MenuItem } from 'primeng/api';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { Divider } from 'primeng/divider';
import { TabsModule } from 'primeng/tabs';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Models
import { Editorial } from '@/editorials/model/editorial';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { EditorialService } from '@/editorials/services/editorial.service';
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
  templateUrl: './editorial-view.page.html',
  styleUrl: './editorial-view.page.less',
})
export default class EditorialViewPage extends AbstractViewDeletePage<Editorial> {
  private editorialService = inject(EditorialService);

  editorial = input.required<Editorial>();

  breadcrumb: MenuItem[] = [
    { label: 'Editoriales', routerLink: '/editorials' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Editorial> {
    return new EraserData<Editorial>(
      this.editorial(),
      this.editorial().name,
      this.editorialService,
      EntityMessages.delete('Editorial', NounGenre.female),
    );
  }

  override getPathBack(): string[] {
    return ['/editorials'];
  }
}
