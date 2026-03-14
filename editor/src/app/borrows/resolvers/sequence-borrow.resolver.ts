import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
import { SequenceService } from '@/shared/services/sequence.service';
import { SequenceType } from '@/shared/models/sequence-type';

export const sequenceBorrowResolver: ResolveFn<string> = () => {
  const sequenceService = inject(SequenceService);
  return sequenceService.simulateSequence(SequenceType.BORROW);
};
