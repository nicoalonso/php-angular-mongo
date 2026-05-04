import { EntityServiceStub } from '@tests/doubles/services/entity-service.stub';
import { Borrow } from '@/borrows/model/borrow';
import { BorrowMother } from '@tests/fixtures/mothers/borrow.mother';
import { Ref } from '@tests/fixtures/ref';
import { BorrowCheckinPayload } from '@/borrows/model/borrow-checkin-payload';
import { Observable, of } from 'rxjs';

export class BorrowServiceStub extends EntityServiceStub<Borrow> {
  public manualPenaltyCalled: boolean = false;

  constructor() {
    super();
  }

  public checkIn(
    _borrowId: string,
    item: BorrowCheckinPayload,
  ): Observable<void> {
    this.updatePayload = item;
    return of(undefined);
  }

  public obtainActiveByCustomer(_customerId: string): Observable<Borrow[]> {
    return of(this.list);
  }

  public manualPenalty(): Observable<void> {
    this.manualPenaltyCalled = true;
    return of(undefined);
  }

  protected makeFixtures(): void {
    const johnDoe = BorrowMother.johnDoe();
    this.addFixture(Ref.BorrowJohnDoe, johnDoe);
  }
}
