import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter, Router } from '@angular/router';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { ConfirmationService } from 'primeng/api';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { ConfirmationServiceStub } from '@tests/doubles/framework/confirmation-service.stub';
import { BorrowService } from '@/borrows/services/borrow.service';
import { ToastService } from '@/shared/services/toast.service';
import BorrowListPage from '@/borrows/pages/borrow-list/borrow-list.page';

describe('BorrowListPage', () => {
  let borrowService: BorrowServiceStub;
  let confirmationService: ConfirmationServiceStub;
  let component: BorrowListPage;
  let fixture: ComponentFixture<BorrowListPage>;

  beforeEach(async () => {
    borrowService = new BorrowServiceStub();
    confirmationService = new ConfirmationServiceStub();

    await TestBed.configureTestingModule({
      imports: [BorrowListPage],
      providers: [
        provideRouter([]),
        { provide: BorrowService, useValue: borrowService },
        { provide: ToastService, useValue: makeToastService() },
        { provide: ConfirmationService, useValue: confirmationService },
      ],
    })
      .overrideComponent(BorrowListPage, {
        set: {
          providers: [
            { provide: ConfirmationService, useValue: confirmationService },
          ],
        },
      })
      .compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BorrowListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    borrowService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    borrowService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nuevo Préstamo',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['borrows', 'create']);
  });

  it('should click on manual penalty', () => {
    borrowService.attachAll();
    fixture.detectChanges();

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Calcular sanciones',
    );
    fireEvent.click(button);

    expect(confirmationService.data).not.toBeNull();
    confirmationService.accept();
    expect(borrowService.manualPenaltyCalled).toBeTruthy();
  });
});
