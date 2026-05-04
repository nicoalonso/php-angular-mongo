import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import { CustomerService } from '@/customers/services/customer.service';
import { CustomerDialogComponent } from '@/customers/dialogs/customer-dialog/customer-dialog.component';
import { Ref } from '@tests/fixtures/ref';

describe('CustomerDialogComponent', () => {
  let customerService: CustomerServiceStub;
  let component: CustomerDialogComponent;
  let fixture: ComponentFixture<CustomerDialogComponent>;

  beforeEach(async () => {
    customerService = new CustomerServiceStub();

    await TestBed.configureTestingModule({
      imports: [CustomerDialogComponent],
      providers: [
        provideAnimationsAsync(),
        { provide: CustomerService, useValue: customerService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(CustomerDialogComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    customerService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add filters', () => {
    customerService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.componentRef.setInput('activeCustomers', true);
    component.form.patchValue({ name: 'test' });
    fixture.detectChanges();

    expect(customerService.query).toEqual({ name: 'test', active: true });
  });

  it('should run when select a customer', (done) => {
    const customer = customerService.attach(Ref.CustomerJohnDoe);
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    component.selected.subscribe((value) => {
      expect(value).toEqual(customer);
      done();
    });

    const event = new MouseEvent('click');
    component.onClickItem(customer, event);

    expect(component.visible()).toBeFalsy();
  });
});
