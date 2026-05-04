import { ActivatedRoute, provideRouter } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import NotFoundPage from '@/main/pages/not-found/not-found.page';
import { BehaviorSubject } from 'rxjs';

describe('NotFoundPage', () => {
  let component: NotFoundPage;
  let fixture: ComponentFixture<NotFoundPage>;
  let queryParams: BehaviorSubject<Record<string, string>>;

  beforeEach(async () => {
    queryParams = new BehaviorSubject({});

    fixture = await TestBed.configureTestingModule({
      imports: [NotFoundPage],
      providers: [
        provideRouter([]),
        {
          provide: ActivatedRoute,
          useValue: { queryParams },
        },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(NotFoundPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should set retryUrl on query params change', () => {
    const testUrl = '/test-url';
    queryParams.next({ url: testUrl });
    fixture.detectChanges();

    expect(component.retryUrl()).toBe(testUrl);
  });
});
