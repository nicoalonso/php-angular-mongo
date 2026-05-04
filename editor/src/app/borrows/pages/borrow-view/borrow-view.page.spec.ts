import { provideRouter } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';
import { BorrowMother } from '@tests/fixtures/mothers/borrow.mother';
import { ToastService } from '@/shared/services/toast.service';
import BorrowViewPage from '@/borrows/pages/borrow-view/borrow-view.page';

describe('BorrowViewPage', () => {
  let component: BorrowViewPage;
  let fixture: ComponentFixture<BorrowViewPage>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BorrowViewPage],
      providers: [
        provideRouter([]),
        { provide: ToastService, useValue: makeToastService() },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(BorrowViewPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const borrow = BorrowMother.johnDoe();
    borrow.createdAt = new Date();
    fixture.componentRef.setInput('borrow', borrow);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
