import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { CustomerService } from '@/customers/services/customer.service';
import { ToastService } from '@/shared/services/toast.service';
import CustomerEditPage from '@/customers/pages/customer-edit/customer-edit.page';

describe('CustomerEditPage', () => {
  let customerService: CustomerServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: CustomerEditPage;
  let fixture: ComponentFixture<CustomerEditPage>;

  beforeEach(async () => {
    customerService = new CustomerServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [CustomerEditPage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: CustomerService, useValue: customerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(CustomerEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const customer = customerService.get(Ref.CustomerJohnDoe);
    fixture.componentRef.setInput('customer', customer);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when persist', () => {
    const customer = customerService.get(Ref.CustomerJohnDoe);
    fixture.componentRef.setInput('customer', customer);
    fixture.detectChanges();

    component.onSave();
    fixture.detectChanges();

    expect(customerService.updatePayload).not.toBeNull();
  });
});
