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
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { EditorialService } from '@/editorials/services/editorial.service';
import EditorialViewPage from '@/editorials/pages/editorial-view/editorial-view.page';

describe('EditorialViewPage', () => {
  let editorialService: EditorialServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: EditorialViewPage;
  let fixture: ComponentFixture<EditorialViewPage>;

  beforeEach(async () => {
    editorialService = new EditorialServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [EditorialViewPage],
      providers: [
        provideRouter([]),
        { provide: EditorialService, useValue: editorialService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(EditorialViewPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(EditorialViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const editorial = editorialService.get(Ref.EditorialAnaya);
    editorial.createdAt = new Date();
    fixture.componentRef.setInput('editorial', editorial);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when remove', () => {
    fixture.componentRef.setInput(
      'editorial',
      editorialService.get(Ref.EditorialAnaya),
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

    expect(editorialService.removed).not.toBeNull();
    expect(navigateSpy).toHaveBeenCalled();
  });
});
