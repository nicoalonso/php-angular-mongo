import { provideRouter } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { Ref } from '@tests/fixtures/ref';
import { BorrowServiceStub } from '@tests/doubles/services/borrow-service.stub';
import { ToastService } from '@/shared/services/toast.service';
import { BorrowService } from '@/borrows/services/borrow.service';
import BorrowCheckinPage from '@/borrows/pages/borrow-checkin/borrow-checkin.page';
import {
  makeToastService,
  ToastServiceMock,
} from '@tests/doubles/services/toast-service.mock';

describe('BorrowCheckinPage', () => {
  let borrowService: BorrowServiceStub;
  let toastServiceMock: ToastServiceMock;
  let component: BorrowCheckinPage;
  let fixture: ComponentFixture<BorrowCheckinPage>;

  beforeEach(async () => {
    borrowService = new BorrowServiceStub();
    toastServiceMock = makeToastService();

    await TestBed.configureTestingModule({
      imports: [BorrowCheckinPage],
      providers: [
        provideRouter([]),
        { provide: BorrowService, useValue: borrowService },
        { provide: ToastService, useValue: toastServiceMock },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BorrowCheckinPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const borrow = borrowService.get(Ref.BorrowJohnDoe);
    fixture.componentRef.setInput('borrow', borrow);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(component.lines).toHaveLength(2);
    expect(fixture).toMatchSnapshot();
  });

  it('should run when save', () => {
    const borrow = borrowService.get(Ref.BorrowJohnDoe);
    fixture.componentRef.setInput('borrow', borrow);
    fixture.detectChanges();

    component.onSave();

    expect(borrowService.updatePayload).not.toBeNull();
  });
});
