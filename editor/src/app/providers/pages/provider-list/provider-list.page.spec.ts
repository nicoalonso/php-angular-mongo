import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter, Router } from '@angular/router';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import ProviderListPage from '@/providers/pages/provider-list/provider-list.page';
import { ProviderService } from '@/providers/services/provider.service';
import { findOneByContent } from '@tests/helpers/search-dom';

describe('ProviderListPage', () => {
  let providerService: ProviderServiceStub;
  let component: ProviderListPage;
  let fixture: ComponentFixture<ProviderListPage>;

  beforeEach(async () => {
    providerService = new ProviderServiceStub();

    await TestBed.configureTestingModule({
      imports: [ProviderListPage],
      providers: [
        provideRouter([]),
        { provide: ProviderService, useValue: providerService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(ProviderListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    providerService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    providerService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nuevo Proveedor',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['providers', 'create']);
  });
});
