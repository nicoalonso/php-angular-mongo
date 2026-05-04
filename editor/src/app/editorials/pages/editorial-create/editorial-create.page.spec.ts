import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { FixturePayload } from '@tests/fixtures/fixture-payload';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { Editorial } from '@/editorials/model/editorial';
import { EditorialService } from '@/editorials/services/editorial.service';
import EditorialCreatePage from '@/editorials/pages/editorial-create/editorial-create.page';

describe('EditorialCreatePage', () => {
  let editorialService: EditorialServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: EditorialCreatePage;
  let fixture: ComponentFixture<EditorialCreatePage>;

  beforeEach(async () => {
    editorialService = new EditorialServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [EditorialCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: EditorialService, useValue: editorialService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(EditorialCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when create', () => {
    const payload = new FixturePayload<Editorial>();
    const data = payload.load('editorial');
    component.form.patchValue(data);
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');
    fixture.detectChanges();

    component.onCreate();

    expect(editorialService.createPayload).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalledWith(['/editorials']);
  });
});
