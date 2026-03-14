import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { SequenceType } from '@/shared/models/sequence-type';
// Services
import { SequenceService } from '@/shared/services/sequence.service';

export const sequenceSaleResolver: ResolveFn<string> = () => {
  const sequenceService = inject(SequenceService);
  return sequenceService.simulateSequence(SequenceType.SALE);
};
