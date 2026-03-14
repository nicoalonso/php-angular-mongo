import {
  ChangeDetectionStrategy,
  Component,
  inject,
  model,
} from '@angular/core';
import {
  NavigationCancel,
  NavigationEnd,
  NavigationError,
  NavigationStart,
  Router,
  RouterOutlet,
} from '@angular/router';
import { toSignal } from '@angular/core/rxjs-interop';
import { debounceTime, filter, map } from 'rxjs';
import { ProgressSpinner } from 'primeng/progressspinner';
import { Toast } from 'primeng/toast';
import { HeaderComponent } from '@/main/layout/header/header.component';
import { UserPanelComponent } from '@/main/layout/user-panel/user-panel.component';
import { MenuAsideComponent } from '@/main/layout/menu-aside/menu-aside.component';

@Component({
  selector: 'app-layout',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    RouterOutlet,
    ProgressSpinner,
    Toast,
    HeaderComponent,
    UserPanelComponent,
    MenuAsideComponent,
  ],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.less',
})
export class LayoutComponent {
  private router = inject(Router);

  private readonly eventTypes = [
    NavigationStart,
    NavigationEnd,
    NavigationCancel,
    NavigationError,
  ];

  menuAside = model<boolean>(false);
  menuUser = model<boolean>(false);

  isNavigating = toSignal<boolean, boolean>(
    this.router.events.pipe(
      filter((event) => this.eventTypes.some((type) => event instanceof type)),
      map((event): boolean => event instanceof NavigationStart),
      debounceTime(200),
    ),
    { initialValue: false },
  );
}
