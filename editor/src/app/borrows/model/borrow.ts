import { Entity } from '@/shared/models/entity';
import { CustomerDescriptor } from '@/customers/model/customer-descriptor';
import { BorrowLine } from '@/borrows/model/borrow-line';
import { SelectOption, findOptionLabel } from '@/shared/models/select-option';

export class Borrow extends Entity {
  public lines: BorrowLine[];

  constructor(
    id: string,
    public customer: CustomerDescriptor,
    public number: string,
    public borrowDate: Date,
    public totalBooks: number,
    public dueDate: Date,
    public totalReturnedBooks: number,
    public returned: boolean,
    public returnedDate: Date | null,
    public penalty: boolean,
    public penaltyAmount: number,
  ) {
    super(id);
    this.lines = [];
  }

  get state(): string {
    return findOptionLabel(borrowStateOptions, this.returned.toString());
  }

  public static from(item: Borrow): Borrow {
    const borrow = new Borrow(
      item.id,
      CustomerDescriptor.from(item.customer),
      item.number,
      new Date(item.borrowDate),
      item.totalBooks,
      new Date(item.dueDate),
      item.totalReturnedBooks,
      item.returned,
      item.returnedDate ? new Date(item.returnedDate) : null,
      item.penalty,
      item.penaltyAmount,
    );

    if (item.lines) {
      borrow.lines = item.lines.map((line) => BorrowLine.from(line));
    }

    borrow.parse(item);
    return borrow;
  }
}

export const borrowStateOptions: SelectOption[] = [
  {
    label: 'En préstamo',
    value: 'false',
  },
  {
    label: 'Retornado',
    value: 'true',
  },
];
