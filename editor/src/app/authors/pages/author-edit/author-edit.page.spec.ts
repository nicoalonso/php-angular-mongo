import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import AuthorEditPage from '@/authors/pages/author-edit/author-edit.page';
import { AuthorService } from '@/authors/services/author.service';
import { ToastService } from '@/shared/services/toast.service';

describe('AuthorEditPage', () => {
  let authorService: AuthorServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: AuthorEditPage;
  let fixture: ComponentFixture<AuthorEditPage>;

  beforeEach(async () => {
    authorService = new AuthorServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [AuthorEditPage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: AuthorService, useValue: authorService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(AuthorEditPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const author = authorService.get(Ref.AuthorShakespeare);
    fixture.componentRef.setInput('author', author);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should fail when persist is called and form is invalid', () => {
    const author = authorService.get(Ref.AuthorShakespeare);
    fixture.componentRef.setInput('author', author);
    fixture.detectChanges();

    component.form.patchValue({ name: '' });
    component.onSave();
    fixture.detectChanges();

    expect(authorService.updatePayload).toBeNull();
    expect(toastServiceMock.topCenter).toHaveBeenCalled();
    expect(toastServiceMock.warn).toHaveBeenCalled();
  });

  it('should run when persist is called and form is valid', () => {
    const author = authorService.get(Ref.AuthorShakespeare);
    fixture.componentRef.setInput('author', author);
    fixture.detectChanges();

    component.onSave();
    fixture.detectChanges();

    expect(authorService.updatePayload).not.toBeNull();
    expect(toastServiceMock.topCenter).not.toHaveBeenCalled();
    expect(toastServiceMock.warn).not.toHaveBeenCalled();
  });
});
