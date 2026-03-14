import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { RouterLink } from '@angular/router';
import { DatePipe } from '@angular/common';
// Framework
import { ConfirmDialog } from 'primeng/confirmdialog';
import { ConfirmationService, MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Models
import { Author } from '@/authors/model/author';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { AuthorService } from '@/authors/services/author.service';
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
    FaIconComponent,
    TrackingSectionComponent,
    DefaultValuePipe,
    BtnCopyComponent,
    DatePipe,
  ],
  providers: [ConfirmationService],
  templateUrl: './author-view.page.html',
  styleUrl: './author-view.page.less',
})
export default class AuthorViewPage extends AbstractViewDeletePage<Author> {
  private authorService = inject(AuthorService);

  author = input.required<Author>();

  breadcrumb: MenuItem[] = [
    { label: 'Autores', routerLink: '/authors' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Author> {
    return new EraserData<Author>(
      this.author(),
      this.author().name,
      this.authorService,
      EntityMessages.delete('Autor', NounGenre.male),
    );
  }

  override getPathBack(): string[] {
    return ['/authors'];
  }
}
