import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import { fireEvent } from '@testing-library/dom';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { ToastService } from '@/shared/services/toast.service';
import AuthorViewPage from '@/authors/pages/author-view/author-view.page';
import { AuthorService } from '@/authors/services/author.service';
import { ConfirmationService } from 'primeng/api';
import { ConfirmationServiceStub } from '@tests/doubles/framework/confirmation-service.stub';
import { findOneByContent } from '@tests/helpers/search-dom';

describe('AuthorViewPage', () => {
  let authorService: AuthorServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: AuthorViewPage;
  let fixture: ComponentFixture<AuthorViewPage>;

  beforeEach(async () => {
    authorService = new AuthorServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [AuthorViewPage],
      providers: [
        provideRouter([]),
        { provide: AuthorService, useValue: authorService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(AuthorViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(AuthorViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const author = authorService.get(Ref.AuthorShakespeare);
    author.createdAt = new Date();
    fixture.componentRef.setInput('author', author);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when go back', () => {
    fixture.componentRef.setInput(
      'author',
      authorService.get(Ref.AuthorShakespeare),
    );
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigateByUrl');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Salir',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalled();
  });

  it('should run when edit', () => {
    fixture.componentRef.setInput(
      'author',
      authorService.get(Ref.AuthorShakespeare),
    );
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigateByUrl');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Editar',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalled();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput(
      'author',
      authorService.get(Ref.AuthorShakespeare),
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

    expect(authorService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
