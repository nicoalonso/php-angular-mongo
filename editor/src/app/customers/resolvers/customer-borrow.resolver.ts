import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Borrow } from '@/borrows/model/borrow';
// Services
import { BorrowService } from '@/borrows/services/borrow.service';

export const customerBorrowResolver: ResolveFn<Borrow[]> = (route) => {
  const borrowService = inject(BorrowService);
  const customerId = route.paramMap.get('id')!;
  return borrowService.obtainActiveByCustomer(customerId);
};
