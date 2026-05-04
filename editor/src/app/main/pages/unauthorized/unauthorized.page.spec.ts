import { provideRouter } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import UnauthorizedPage from '@/main/pages/unauthorized/unauthorized.page';

describe('UnauthorizedPage', () => {
  let component: UnauthorizedPage;
  let fixture: ComponentFixture<UnauthorizedPage>;

  beforeEach(async () => {
    fixture = await TestBed.configureTestingModule({
      imports: [UnauthorizedPage],
      providers: [provideRouter([])],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(UnauthorizedPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
