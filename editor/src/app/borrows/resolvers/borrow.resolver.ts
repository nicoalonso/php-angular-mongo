import { ResolveFn } from '@angular/router';
import { Borrow } from '@/borrows/model/borrow';
import { inject } from '@angular/core';
import { BorrowService } from '@/borrows/services/borrow.service';

export const borrowResolver: ResolveFn<Borrow> = (route) => {
  const borrowService = inject(BorrowService);
  const borrowId = route.paramMap.get('id')!;
  return borrowService.getItem(borrowId);
};
