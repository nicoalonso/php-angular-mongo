import {
  ActivatedRouteSnapshot,
  ResolveFn,
  RouterStateSnapshot,
} from '@angular/router';
import { TestBed } from '@angular/core/testing';
import { isObservable } from 'rxjs';
import { Ref } from '@tests/fixtures/ref';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { Customer } from '@/customers/model/customer';
import { customerResolver } from '@/customers/resolvers/customer.resolver';
import { CustomerService } from '@/customers/services/customer.service';

describe('customerResolver', () => {
  let customerService: CustomerServiceStub;

  const executeResolver: ResolveFn<Customer> = (...resolverParameters) =>
    TestBed.runInInjectionContext(() =>
      customerResolver(...resolverParameters),
    );

  beforeEach(() => {
    customerService = new CustomerServiceStub();
    TestBed.configureTestingModule({
      providers: [{ provide: CustomerService, useValue: customerService }],
    });
  });

  it('should null when customer not found', (done) => {
    const route = {
      paramMap: new Map([['id', 'non-existent']]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((customer) => {
        expect(customer).toBeNull();
        done();
      });
    }
  });

  it('should run when customer found', (done) => {
    const customer = customerService.put(Ref.CustomerJohnDoe);

    const route = {
      paramMap: new Map([['id', customer.id]]),
    } as unknown as ActivatedRouteSnapshot;
    const state = {} as RouterStateSnapshot;

    const result = executeResolver(route, state);

    expect(isObservable(result)).toBeTruthy();
    if (isObservable(result)) {
      result.subscribe((customer) => {
        expect(customer).not.toBeNull();
        done();
      });
    }
  });
});
