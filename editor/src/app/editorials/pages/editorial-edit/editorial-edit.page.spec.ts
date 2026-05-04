import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { ToastService } from '@/shared/services/toast.service';
import { EditorialService } from '@/editorials/services/editorial.service';
import EditorialEditPage from '@/editorials/pages/editorial-edit/editorial-edit.page';
import { Ref } from '@tests/fixtures/ref';

describe('EditorialEditPage', () => {
  let editorialService: EditorialServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: EditorialEditPage;
  let fixture: ComponentFixture<EditorialEditPage>;

  beforeEach(async () => {
    editorialService = new EditorialServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [EditorialEditPage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: EditorialService, useValue: editorialService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(EditorialEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const editorial = editorialService.get(Ref.EditorialAnaya);
    fixture.componentRef.setInput('editorial', editorial);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when create', () => {
    const editorial = editorialService.get(Ref.EditorialAnaya);
    fixture.componentRef.setInput('editorial', editorial);
    fixture.detectChanges();

    component.onSave();

    expect(editorialService.updatePayload).not.toBeNull();
  });
});
