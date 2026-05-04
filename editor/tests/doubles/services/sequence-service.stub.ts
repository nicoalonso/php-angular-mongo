import { SequenceType } from '@/shared/models/sequence-type';
import { Observable, of } from 'rxjs';

export class SequenceServiceStub {
  simulateSequence(type: SequenceType): Observable<string> {
    let prefix = 'SEQ';
    switch (type) {
      case SequenceType.MEMBERSHIP:
        prefix = 'SN';
        break;
      case SequenceType.SALE:
        prefix = 'F-';
        break;
      case SequenceType.BORROW:
        prefix = 'P-';
        break;
    }

    return of(`${prefix}00001`);
  }
}
