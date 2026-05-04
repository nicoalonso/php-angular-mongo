import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { CustomerDescriptorMother } from '@tests/fixtures/mothers/customer.mother';
import { Borrow } from '@/borrows/model/borrow';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { BorrowLineMother } from '@tests/fixtures/mothers/borrow-line.mother';

const JOHN_DOE = {
  id: 'e7bbc531-af6c-4cf9-a4e2-ef66595535f4',
  customer: CustomerDescriptorMother.johnDoe,
  number: 'P-00022',
  borrowDate: ['2026-03-31T10:25:36', MotherMapping.DATE],
  totalBooks: 3,
  dueDate: ['2026-04-15T10:25:36', MotherMapping.DATE],
  totalReturnedBooks: 0,
  returned: false,
  returnedDate: null,
  penalty: false,
  penaltyAmount: 0,
  lines: [
    MotherMapping.ARRAY,
    [BorrowLineMother.romeoAndJuliet, BorrowLineMother.donQuixote],
  ],
  createdBy: 'test',
  createdAt: ['2026-04-28T10:25:36', MotherMapping.DATE],
};

type BorrowFixture = typeof JOHN_DOE;

export class BorrowMother extends BaseMother {
  static johnDoe(this: void, overrides?: Partial<Borrow>): Borrow {
    return BorrowMother.create(JOHN_DOE, overrides);
  }

  protected static create(
    values: BorrowFixture,
    overrides?: Partial<Borrow>,
  ): Borrow {
    const fields: Borrow = BorrowMother.merge<BorrowFixture, Borrow>(
      values,
      overrides,
    );

    return Borrow.from(fields);
  }
}
