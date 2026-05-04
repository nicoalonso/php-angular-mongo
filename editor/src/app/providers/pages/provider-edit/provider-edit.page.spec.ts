import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { ProviderService } from '@/providers/services/provider.service';
import ProviderEditPage from '@/providers/pages/provider-edit/provider-edit.page';
import { Ref } from '@tests/fixtures/ref';

describe('ProviderEditPage', () => {
  let providerService: ProviderServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: ProviderEditPage;
  let fixture: ComponentFixture<ProviderEditPage>;

  beforeEach(async () => {
    providerService = new ProviderServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [ProviderEditPage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: ProviderService, useValue: providerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(ProviderEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const provider = providerService.get(Ref.ProviderAmazon);
    fixture.componentRef.setInput('provider', provider);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when update', () => {
    const provider = providerService.get(Ref.ProviderAmazon);
    fixture.componentRef.setInput('provider', provider);
    fixture.detectChanges();

    component.onSave();

    expect(providerService.updatePayload).not.toBeNull();
  });
});
