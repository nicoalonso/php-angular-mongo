import {
  ChangeDetectionStrategy,
  Component,
  computed,
  inject,
  model,
} from '@angular/core';
import { Drawer } from 'primeng/drawer';
import { NgOptimizedImage } from '@angular/common';
import { environment } from '@environments/environment';
import { UserService } from '@/shared/services/user.service';

@Component({
  selector: 'app-user-panel',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [Drawer, NgOptimizedImage],
  templateUrl: './user-panel.component.html',
  styleUrl: './user-panel.component.less',
})
export class UserPanelComponent {
  protected readonly version = environment.app.version;

  userService = inject(UserService);

  showPanel = model.required<boolean>();
  userName = computed(() => this.userService.user?.displayName);
  userEmail = computed(() => this.userService.user?.name);
  userRole = computed(() => this.userService.user?.role);
}
