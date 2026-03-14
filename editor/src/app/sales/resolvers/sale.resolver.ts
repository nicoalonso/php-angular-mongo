import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Sale } from '@/sales/model/sale';
// Services
import { SaleService } from '@/sales/services/sale.service';

export const saleResolver: ResolveFn<Sale> = (route) => {
  const saleService = inject(SaleService);
  const saleId = route.paramMap.get('id')!;
  return saleService.getItem(saleId);
};
