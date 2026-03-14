import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { MutationService } from '@/shared/interfaces/mutation-service';
// Models
import { Borrow } from '@/borrows/model/borrow';
import { ListQuery } from '@/shared/interfaces/list-query';
import { BorrowCheckinPayload } from '@/borrows/model/borrow-checkin-payload';

@Injectable({
  providedIn: 'root',
})
export class BorrowService extends MutationService<Borrow> {
  private readonly resource = 'borrows';

  public checkIn(
    borrowId: string,
    item: BorrowCheckinPayload,
  ): Observable<void> {
    const url = this.buildEndpoint(borrowId);
    return this.httpClient.patch<void>(url, item);
  }

  public obtainActiveByCustomer(customerId: string): Observable<Borrow[]> {
    const query: ListQuery = { customerId, returned: false };
    return this.search(query).pipe(map((result) => result.items));
  }

  public manualPenalty(): Observable<void> {
    const url = this.buildEndpoint('manual-penalty');
    return this.httpClient.post<void>(url, {});
  }

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Borrow): Borrow {
    return Borrow.from(item);
  }
}
