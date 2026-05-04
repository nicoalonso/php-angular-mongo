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
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { ProviderService } from '@/providers/services/provider.service';
import ProviderViewPage from '@/providers/pages/provider-view/provider-view.page';

describe('ProviderViewPage', () => {
  let providerService: ProviderServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: ProviderViewPage;
  let fixture: ComponentFixture<ProviderViewPage>;

  beforeEach(async () => {
    providerService = new ProviderServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [ProviderViewPage],
      providers: [
        provideRouter([]),
        { provide: ProviderService, useValue: providerService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(ProviderViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(ProviderViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const provider = providerService.get(Ref.ProviderAmazon);
    provider.createdAt = new Date();
    fixture.componentRef.setInput('provider', provider);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput(
      'provider',
      providerService.get(Ref.ProviderAmazon),
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

    expect(providerService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
