import { provideRouter } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { SaleMother } from '@tests/fixtures/mothers/sale.mother';
import { ToastService } from '@/shared/services/toast.service';
import SaleViewPage from '@/sales/pages/sale-view/sale-view.page';

describe('SaleViewPage', () => {
  let component: SaleViewPage;
  let fixture: ComponentFixture<SaleViewPage>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SaleViewPage],
      providers: [
        provideRouter([]),
        { provide: ToastService, useValue: makeToastService() },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(SaleViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const sale = SaleMother.johnDoeSale1();
    sale.createdAt = new Date();
    fixture.componentRef.setInput('sale', sale);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
