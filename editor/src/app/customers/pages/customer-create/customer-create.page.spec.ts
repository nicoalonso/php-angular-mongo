import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { FixturePayload } from '@tests/fixtures/fixture-payload';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { Customer } from '@/customers/model/customer';
import { CustomerService } from '@/customers/services/customer.service';
import CustomerCreatePage from '@/customers/pages/customer-create/customer-create.page';

describe('CustomerCreatePage', () => {
  let customerService: CustomerServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: CustomerCreatePage;
  let fixture: ComponentFixture<CustomerCreatePage>;

  beforeEach(async () => {
    customerService = new CustomerServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [CustomerCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: CustomerService, useValue: customerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(CustomerCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when create customer', () => {
    const payload = new FixturePayload<Customer>();
    const data = payload.load('customer-create');
    component.form.patchValue(data);
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    component.onCreate();

    expect(customerService.createPayload).not.toBeNull();
    const createPayload = customerService.createPayload! as Customer;
    expect(createPayload.name).toBe(data.name);
    expect(navigateSpy).toHaveBeenCalledWith(['/customers']);
  });
});
