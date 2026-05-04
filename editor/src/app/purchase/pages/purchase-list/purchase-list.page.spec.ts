import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { PurchaseServiceStub } from '@tests/doubles/services/purchase-service.stub';
import { PurchaseService } from '@/purchase/services/purchase.service';
import PurchaseListPage from '@/purchase/pages/purchase-list/purchase-list.page';

describe('PurchaseListPage', () => {
  let purchaseService: PurchaseServiceStub;
  let component: PurchaseListPage;
  let fixture: ComponentFixture<PurchaseListPage>;

  beforeEach(async () => {
    purchaseService = new PurchaseServiceStub();

    await TestBed.configureTestingModule({
      imports: [PurchaseListPage],
      providers: [
        provideRouter([]),
        { provide: PurchaseService, useValue: purchaseService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(PurchaseListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    purchaseService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    purchaseService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nueva Entrada',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['purchases', 'create']);
  });
});
