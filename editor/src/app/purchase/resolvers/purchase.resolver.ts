import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Purchase } from '@/purchase/model/purchase';
// Services
import { PurchaseService } from '@/purchase/services/purchase.service';

export const purchaseResolver: ResolveFn<Purchase> = (route) => {
  const purchaseService = inject(PurchaseService);
  const purchaseId = route.paramMap.get('id')!;
  return purchaseService.getItem(purchaseId);
};
