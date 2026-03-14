import { effect, inject, Injectable, signal } from '@angular/core';
import { DOCUMENT } from '@angular/common';

@Injectable({
  providedIn: 'root',
})
export class ThemeService {
  private readonly document = inject(DOCUMENT);
  private readonly themeStorageKey = 'theme';
  private readonly darkModeClass = 'dark-mode';

  #isLightTheme = signal<boolean>(this.getInitialTheme());

  constructor() {
    effect(() => {
      const isLight = this.#isLightTheme();
      this.applyTheme(isLight);
      this.saveTheme(isLight);
    });
  }

  get isLightTheme(): boolean {
    return this.#isLightTheme();
  }

  switchTheme(): void {
    this.#isLightTheme.update((value) => !value);
  }

  private getInitialTheme(): boolean {
    const theme = localStorage.getItem(this.themeStorageKey);
    return theme === 'light';
  }

  private applyTheme(isLight: boolean): void {
    this.document.documentElement.classList.toggle(
      this.darkModeClass,
      !isLight,
    );
  }

  private saveTheme(isLight: boolean): void {
    localStorage.setItem(this.themeStorageKey, isLight ? 'light' : 'dark');
  }
}
