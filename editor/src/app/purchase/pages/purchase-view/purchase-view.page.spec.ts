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
import { ConfirmationServiceStub } from '@tests/doubles/framework/confirmation-service.stub';
import { PurchaseServiceStub } from '@tests/doubles/services/purchase-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { PurchaseService } from '@/purchase/services/purchase.service';
import PurchaseViewPage from '@/purchase/pages/purchase-view/purchase-view.page';

describe('PurchaseViewPage', () => {
  let purchaseService: PurchaseServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: PurchaseViewPage;
  let fixture: ComponentFixture<PurchaseViewPage>;

  beforeEach(async () => {
    purchaseService = new PurchaseServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [PurchaseViewPage],
      providers: [
        provideRouter([]),
        { provide: PurchaseService, useValue: purchaseService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(PurchaseViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(PurchaseViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const purchase = purchaseService.get(Ref.PurchaseAmazonInv1);
    purchase.createdAt = new Date();
    fixture.componentRef.setInput('purchase', purchase);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput(
      'purchase',
      purchaseService.get(Ref.PurchaseAmazonInv1),
    );
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

    expect(purchaseService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
