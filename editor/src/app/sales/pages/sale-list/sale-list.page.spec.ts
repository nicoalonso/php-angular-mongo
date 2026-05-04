import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { SaleServiceStub } from '@tests/doubles/services/sale-service.stub';
import { SaleService } from '@/sales/services/sale.service';
import SaleListPage from '@/sales/pages/sale-list/sale-list.page';

describe('SaleListPage', () => {
  let saleService: SaleServiceStub;
  let component: SaleListPage;
  let fixture: ComponentFixture<SaleListPage>;

  beforeEach(async () => {
    saleService = new SaleServiceStub();

    await TestBed.configureTestingModule({
      imports: [SaleListPage],
      providers: [
        provideRouter([]),
        { provide: SaleService, useValue: saleService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(SaleListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    saleService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    saleService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nueva Venta',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['sales', 'create']);
  });
});
