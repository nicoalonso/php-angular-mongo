import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Customer } from '@/customers/model/customer';
// Services
import { CustomerService } from '@/customers/services/customer.service';

export const customerResolver: ResolveFn<Customer> = (route) => {
  const clientService = inject(CustomerService);
  const clientId = route.paramMap.get('id')!;
  return clientService.getItem(clientId);
};
