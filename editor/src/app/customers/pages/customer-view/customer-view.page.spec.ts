import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConfirmationService } from 'primeng/api';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { Ref } from '@tests/fixtures/ref';
import { findOneByContent } from '@tests/helpers/search-dom';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { ConfirmationServiceStub } from '@tests/doubles/framework/confirmation-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { CustomerService } from '@/customers/services/customer.service';
import CustomerViewPage from '@/customers/pages/customer-view/customer-view.page';

describe('CustomerViewPage', () => {
  let customerService: CustomerServiceStub;
  let borrowService: BorrowServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: CustomerViewPage;
  let fixture: ComponentFixture<CustomerViewPage>;

  beforeEach(async () => {
    customerService = new CustomerServiceStub();
    borrowService = new BorrowServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [CustomerViewPage],
      providers: [
        provideRouter([]),
        { provide: CustomerService, useValue: customerService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(CustomerViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(CustomerViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const customer = customerService.get(Ref.CustomerJohnDoe);
    customer.createdAt = new Date();
    fixture.componentRef.setInput('customer', customer);
    fixture.componentRef.setInput('borrows', borrowService.attachAll());
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput(
      'customer',
      customerService.get(Ref.CustomerJohnDoe),
    );
    fixture.componentRef.setInput('borrows', borrowService.attachAll());
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Eliminar',
    );
    fireEvent.click(button);

    expect(confirmationService.data).not.toBeNull();
    confirmationService.accept();

    expect(customerService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
