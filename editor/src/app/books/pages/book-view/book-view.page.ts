import {
  ChangeDetectionStrategy,
  Component,
  inject,
  input,
} from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe } from '@angular/common';
import { RouterLink } from '@angular/router';
// Framework
import { ConfirmationService, MenuItem } from 'primeng/api';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Button } from 'primeng/button';
import { TabsModule } from 'primeng/tabs';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Pages
import { AbstractViewDeletePage } from '@/shared/pages/abstract-view-delete/abstract-view-delete.page';
// Models
import { Book } from '@/books/model/book';
import { EraserData } from '@/shared/models/eraser-data';
import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';
// Services
import { BookService } from '@/books/services/book-service';
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
    Button,
    DateAgoPipe,
    RouterLink,
    TabsModule,
    FaIconComponent,
    TrackingSectionComponent,
    DefaultValuePipe,
    BtnCopyComponent,
    DatePipe,
    CurrencyPipe,
    DecimalPipe,
  ],
  providers: [ConfirmationService],
  templateUrl: './book-view.page.html',
  styleUrl: './book-view.page.less',
})
export default class BookViewPage extends AbstractViewDeletePage<Book> {
  private bookService = inject(BookService);

  book = input.required<Book>();

  breadcrumb: MenuItem[] = [
    { label: 'Libros', routerLink: '/books' },
    { label: 'Detalle', styleClass: 'text-xl font-bold' },
  ];

  override getEraserData(): EraserData<Book> {
    return new EraserData<Book>(
      this.book(),
      this.book().title,
      this.bookService,
      EntityMessages.delete('Libro', NounGenre.male),
    );
  }

  override getPathBack(): string[] {
    return ['/books'];
  }
}
