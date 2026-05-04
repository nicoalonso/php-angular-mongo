import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { FixturePayload } from '@tests/fixtures/fixture-payload';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { Provider } from '@/providers/model/provider';
import { ProviderService } from '@/providers/services/provider.service';
import ProviderCreatePage from '@/providers/pages/provider-create/provider-create.page';

describe('ProviderCreatePage', () => {
  let providerService: ProviderServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: ProviderCreatePage;
  let fixture: ComponentFixture<ProviderCreatePage>;

  beforeEach(async () => {
    providerService = new ProviderServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [ProviderCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: ProviderService, useValue: providerService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(ProviderCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when create', () => {
    const payload = new FixturePayload<Provider>();
    const data = payload.load('provider');
    component.form.patchValue(data);
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    component.onCreate();

    expect(providerService.createPayload).not.toBeNull();
    const createPayload = providerService.createPayload! as Provider;
    expect(createPayload.name).toBe(data.name);
    expect(navigateSpy).toHaveBeenCalledWith(['/providers']);
  });
});
