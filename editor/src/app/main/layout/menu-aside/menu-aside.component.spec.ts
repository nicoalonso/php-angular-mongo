import { provideRouter, Router } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { UserService } from '@/shared/services/user.service';
import { MenuAsideComponent } from '@/main/layout/menu-aside/menu-aside.component';

describe('MenuAsideComponent', () => {
  let component: MenuAsideComponent;
  let fixture: ComponentFixture<MenuAsideComponent>;

  beforeEach(async () => {
    fixture = await TestBed.configureTestingModule({
      imports: [MenuAsideComponent],
      providers: [provideRouter([]), provideAnimationsAsync()],
    }).compileComponents();

    fixture = TestBed.createComponent(MenuAsideComponent);
    component = fixture.componentInstance;
    fixture.componentRef.setInput('menuAside', true);
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should render menu items based on user', () => {
    const userService = TestBed.inject(UserService);
    userService.isAuthenticated().subscribe();
    fixture.detectChanges();

    expect(component.items()).toHaveLength(4);

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate').mockResolvedValue(true);

    const anchor = findOneByContent<HTMLAnchorElement>(
      fixture,
      'a',
      'Listado de clientes',
    );
    fireEvent.click(anchor);

    expect(navigateSpy).toHaveBeenCalledWith(['/customers']);
  });

  it('should hide panel', () => {
    component.onHidePanel();
    fixture.detectChanges();

    expect(component.menuAside()).toBeFalsy();
  });
});
