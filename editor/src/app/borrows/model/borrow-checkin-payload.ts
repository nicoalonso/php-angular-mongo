import { EntityPayload } from '@/shared/models/entity';

export interface BorrowLinePayload {
  lineId: string;
  bookId: string;
  returned: boolean;
}

export interface BorrowCheckinPayload extends EntityPayload {
  lines: BorrowLinePayload[];
}
