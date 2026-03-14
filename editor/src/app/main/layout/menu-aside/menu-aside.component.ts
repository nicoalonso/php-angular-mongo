import {
  ChangeDetectionStrategy,
  Component,
  effect,
  inject,
  model,
  signal,
} from '@angular/core';
import { Router } from '@angular/router';
import { NgOptimizedImage } from '@angular/common';
import { Drawer } from 'primeng/drawer';
import { Menu } from 'primeng/menu';
import { MenuItem } from 'primeng/api';
// App
import { UserService } from '@/shared/services/user.service';
import { environment } from '@environments/environment';
import { getMainMenuByUser } from '@/main/layout/menu-aside/menu';

@Component({
  selector: 'app-menu-aside',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [NgOptimizedImage, Drawer, Menu],
  templateUrl: './menu-aside.component.html',
  styleUrl: './menu-aside.component.less',
})
export class MenuAsideComponent {
  protected readonly version = environment.app.version;

  userService = inject(UserService);
  router = inject(Router);

  menuAside = model.required<boolean>();
  items = signal<MenuItem[]>([]);

  constructor() {
    this.userEffect();
  }

  private userEffect(): void {
    effect(() => {
      if (!this.userService.user) {
        return;
      }

      const items = getMainMenuByUser(this.userService.user);
      this.items.set(this.menuMapper(items));
    });
  }

  private menuMapper(items: MenuItem[]): MenuItem[] {
    return items.map((item) => {
      const {
        icon = '',
        label = '',
        routerLink = '',
        items = [],
        separator = false,
      } = item;
      if (separator) {
        return { separator: true };
      }

      let validItems: MenuItem[] = [];
      if (items.length) {
        validItems = this.menuMapper(items);
      }

      return {
        label,
        icon,
        command: () => this.moveToPath(routerLink),
        items: validItems,
      };
    });
  }

  moveToPath(path: string): void {
    this.router.navigate([path]).then(() => this.onHidePanel());
  }

  onHidePanel(): void {
    this.menuAside.set(false);
  }
}
