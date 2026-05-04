import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ProviderServiceStub } from '@tests/doubles/services/provider-service.stub';
import { ProviderService } from '@/providers/services/provider.service';
import { ProviderDialogComponent } from '@/providers/dialog/provider-dialog/provider-dialog.component';
import { Ref } from '@tests/fixtures/ref';

describe('ProviderDialogComponent', () => {
  let providerService: ProviderServiceStub;
  let component: ProviderDialogComponent;
  let fixture: ComponentFixture<ProviderDialogComponent>;

  beforeEach(async () => {
    providerService = new ProviderServiceStub();

    await TestBed.configureTestingModule({
      imports: [ProviderDialogComponent],
      providers: [
        provideAnimationsAsync(),
        { provide: ProviderService, useValue: providerService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ProviderDialogComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    providerService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add filters', () => {
    providerService.attachAll();
    fixture.componentRef.setInput('visible', true);
    component.form.patchValue({ name: 'test' });
    fixture.detectChanges();

    expect(providerService.query).toEqual({ name: 'test' });
  });

  it('should run when select a provider', (done) => {
    const provider = providerService.attach(Ref.ProviderAmazon);
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    component.selected.subscribe((value) => {
      expect(value).toEqual(provider);
      done();
    });

    const event = new MouseEvent('click');
    component.onClickItem(provider, event);

    expect(component.visible()).toBeFalsy();
  });
});
