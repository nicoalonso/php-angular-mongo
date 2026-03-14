import { Injectable } from '@angular/core';
// Models
import { Customer } from '@/customers/model/customer';
// Services
import { MutationService } from '@/shared/interfaces/mutation-service';

@Injectable({
  providedIn: 'root',
})
export class CustomerService extends MutationService<Customer> {
  private readonly resource = 'customers';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Customer): Customer {
    return Customer.from(item);
  }
}
