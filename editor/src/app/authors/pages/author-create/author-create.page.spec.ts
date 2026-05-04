import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';
import { AuthorService } from '@/authors/services/author.service';
import AuthorCreatePage from '@/authors/pages/author-create/author-create.page';
import { ToastService } from '@/shared/services/toast.service';
import { Author } from '@/authors/model/author';
import { FixturePayload } from '@tests/fixtures/fixture-payload';
import { Book } from '@/books/model/book';

describe('AuthorCreatePage', () => {
  let authorService: AuthorServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: AuthorCreatePage;
  let fixture: ComponentFixture<AuthorCreatePage>;

  beforeEach(async () => {
    authorService = new AuthorServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [AuthorCreatePage],
      providers: [
        provideRouter([]),
        provideAnimationsAsync(),
        { provide: AuthorService, useValue: authorService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(AuthorCreatePage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should fail when next step is called and form is invalid', () => {
    component.onNextStep();
    fixture.detectChanges();

    expect(component.activeStep()).toBe(0);
    expect(toastServiceMock.topCenter).toHaveBeenCalled();
    expect(toastServiceMock.warn).toHaveBeenCalled();
  });

  it('should run when next step', () => {
    component.form.patchValue({ name: 'test', realName: 'test' });
    component.onNextStep();
    fixture.detectChanges();

    expect(component.activeStep()).toBe(1);
    expect(toastServiceMock.warn).not.toHaveBeenCalled();
  });

  it('should run when previous step', () => {
    component.form.patchValue({
      name: 'test',
      realName: 'test',
      birthDate: new Date(),
      photoUrl: 'https://example.com/photo.jpg',
      website: 'https://example.com',
    });
    component.onNextStep();
    component.onPreviousStep();
    fixture.detectChanges();

    expect(component.activeStep()).toBe(0);
    expect(toastServiceMock.warn).not.toHaveBeenCalled();
  });

  it('should fail when invalid form when creating', () => {
    component.onCreate();
    fixture.detectChanges();

    expect(authorService.createPayload).toBeNull();
  });

  it('should run when create author', () => {
    const payload = new FixturePayload<Book>();
    const data = payload.load('author');
    component.form.patchValue(data);
    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    component.onCreate();

    expect(authorService.createPayload).not.toBeNull();
    const createPayload = authorService.createPayload! as Author;
    expect(createPayload.birthDate).toBe('1564-04-23');
    expect(navigateSpy).toHaveBeenCalledWith(['/authors']);
  });
});
