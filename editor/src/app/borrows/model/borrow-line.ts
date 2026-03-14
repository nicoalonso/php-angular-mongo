import { BookDescriptor } from '@/books/model/book-descriptor';
import { findOptionLabel } from '@/shared/models/select-option';
import { borrowStateOptions } from '@/borrows/model/borrow';

export class BorrowLine {
  constructor(
    public lineId: string,
    public book: BookDescriptor,
    public returned: boolean,
    public returnedDate: Date | null,
    public penalty: boolean,
    public penaltyAmount: number,
  ) {}

  get state(): string {
    return findOptionLabel(borrowStateOptions, this.returned.toString());
  }

  public static empty(): BorrowLine {
    return new BorrowLine('', BookDescriptor.empty(), false, null, false, 0);
  }

  public static from(item: BorrowLine): BorrowLine {
    return new BorrowLine(
      item.lineId,
      BookDescriptor.from(item.book),
      item.returned,
      item.returnedDate ? new Date(item.returnedDate) : null,
      item.penalty,
      item.penaltyAmount,
    );
  }
}
