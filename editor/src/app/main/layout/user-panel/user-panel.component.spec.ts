import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { UserPanelComponent } from '@/main/layout/user-panel/user-panel.component';
import { UserService } from '@/shared/services/user.service';

describe('UserPanelComponent', () => {
  let component: UserPanelComponent;
  let fixture: ComponentFixture<UserPanelComponent>;

  beforeEach(async () => {
    fixture = await TestBed.configureTestingModule({
      imports: [UserPanelComponent],
      providers: [provideAnimationsAsync()],
    }).compileComponents();

    fixture = TestBed.createComponent(UserPanelComponent);
    component = fixture.componentInstance;
    fixture.componentRef.setInput('showPanel', true);
  });

  it('should create', () => {
    const userService = TestBed.inject(UserService);
    userService.isAuthenticated().subscribe();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
