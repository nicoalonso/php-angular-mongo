import { Injectable } from '@angular/core';
import { MutationService } from '@/shared/interfaces/mutation-service';
import { Book } from '@/books/model/book';
import { catchError, map, Observable, of } from 'rxjs';
import { ApiResponse } from '@/shared/interfaces/api-response';

interface BookAvailabilityResponse {
  available: boolean;
}

@Injectable({
  providedIn: 'root',
})
export class BookService extends MutationService<Book> {
  private readonly resource = 'books';

  checkAvailability(
    bookId: string,
    isSale: boolean = false,
  ): Observable<boolean> {
    let url = this.buildEndpoint(bookId) + '/available';
    if (isSale) {
      url += '?sale=true';
    }

    return this.httpClient.get<ApiResponse<BookAvailabilityResponse>>(url).pipe(
      map((resp) => resp.data.available),
      catchError(() => of(false)),
    );
  }

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Book): Book {
    return Book.from(item);
  }
}
