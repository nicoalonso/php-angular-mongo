import { BookDescriptorMother } from '@tests/fixtures/mothers/book.mother';
import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { BorrowLine } from '@/borrows/model/borrow-line';

const ROMEO_AND_JULIET = {
  lineId: 'cca6ad12-379d-4107-b460-dd1cb6506cb0',
  book: BookDescriptorMother.romeoAndJuliet,
  returned: false,
  returnedDate: null,
  penalty: false,
  penaltyAmount: 0,
};

const DON_QUIXOTE = {
  lineId: '752fafed-f1f2-4868-a452-a1db7acdfdbd',
  book: BookDescriptorMother.donQuixote,
  returned: false,
  returnedDate: null,
  penalty: false,
  penaltyAmount: 0,
};

type BorrowLineFixture = typeof ROMEO_AND_JULIET;

export class BorrowLineMother extends BaseMother {
  static romeoAndJuliet(
    this: void,
    overrides?: Partial<BorrowLine>,
  ): BorrowLine {
    return BorrowLineMother.create(ROMEO_AND_JULIET, overrides);
  }

  static donQuixote(this: void, overrides?: Partial<BorrowLine>): BorrowLine {
    return BorrowLineMother.create(DON_QUIXOTE, overrides);
  }

  protected static create(
    values: BorrowLineFixture,
    overrides?: Partial<BorrowLine>,
  ) {
    const fields: BorrowLine = BorrowLineMother.merge<
      BorrowLineMother,
      BorrowLine
    >(values, overrides);

    return BorrowLine.from(fields);
  }
}
