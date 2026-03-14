import { Component, computed, inject, output } from '@angular/core';
import { ThemeService } from '@/main/services/theme.service';
import { Menubar } from 'primeng/menubar';
import { PrimeTemplate } from 'primeng/api';
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
import { Tooltip } from 'primeng/tooltip';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-header',
  imports: [
    Menubar,
    PrimeTemplate,
    Button,
    Tooltip,
    FaIconComponent,
    RouterLink,
  ],
  templateUrl: './header.component.html',
  styleUrl: './header.component.less',
})
export class HeaderComponent {
  private themeService = inject(ThemeService);

  public isLightTheme = computed<boolean>(() => this.themeService.isLightTheme);

  showMenu = output<void>();
  showUserPanel = output<void>();

  changeTheme(): void {
    this.themeService.switchTheme();
  }
}
