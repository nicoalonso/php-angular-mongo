import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { CustomerServiceStub } from '@tests/doubles/services/customer-service.stub';
import CustomerListPage from '@/customers/pages/customer-list/customer-list.page';
import { CustomerService } from '@/customers/services/customer.service';
import { provideRouter, Router } from '@angular/router';
import { findOneByContent } from '@tests/helpers/search-dom';
import { fireEvent } from '@testing-library/dom';

describe('CustomerListPage', () => {
  let customerService: CustomerServiceStub;
  let component: CustomerListPage;
  let fixture: ComponentFixture<CustomerListPage>;

  beforeEach(async () => {
    customerService = new CustomerServiceStub();

    await TestBed.configureTestingModule({
      imports: [CustomerListPage],
      providers: [
        provideRouter([]),
        { provide: CustomerService, useValue: customerService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(CustomerListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    customerService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    customerService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nuevo Cliente',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['customers', 'create']);
  });
});
