import {
  ApplicationConfig,
  importProvidersFrom,
  inject,
  provideExperimentalZonelessChangeDetection,
  LOCALE_ID,
} from '@angular/core';
import { registerLocaleData } from '@angular/common';
import localeEs from '@angular/common/locales/es';
import {
  provideRouter,
  Router,
  withComponentInputBinding,
  withNavigationErrorHandler,
  withViewTransitions,
} from '@angular/router';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {
  provideHttpClient,
  withFetch,
  withInterceptors,
  withInterceptorsFromDi,
} from '@angular/common/http';
import { provideTranslateService } from '@ngx-translate/core';
import { provideTranslateHttpLoader } from '@ngx-translate/http-loader';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { providePrimeNG } from 'primeng/config';
import { MessageService } from 'primeng/api';
// Themes
import { LaraBlue } from '@/shared/presets/primeng';
// Routes
import { routes } from './app.routes';
import { authInterceptor } from '@/shared/interceptors/auth.interceptor';

registerLocaleData(localeEs, 'es-ES');

export const appConfig: ApplicationConfig = {
  providers: [
    { provide: LOCALE_ID, useValue: 'es-ES' },
    provideExperimentalZonelessChangeDetection(),
    provideRouter(
      routes,
      withComponentInputBinding(),
      withViewTransitions(),
      withNavigationErrorHandler((error) => {
        const router = inject(Router);
        console.error(error);
        router
          .navigate(['/not-found'], {
            queryParams: {
              url: error.url,
            },
          })
          .then();
      }),
    ),
    importProvidersFrom([BrowserModule, BrowserAnimationsModule]),
    provideHttpClient(
      withInterceptors([authInterceptor]),
      withInterceptorsFromDi(),
      withFetch(),
    ),
    provideTranslateService({
      loader: provideTranslateHttpLoader({
        prefix: 'i18n/',
        suffix: '.json',
      }),
      fallbackLang: 'es',
      lang: 'es',
    }),
    provideAnimationsAsync(),
    providePrimeNG({
      ripple: true,
      theme: {
        preset: LaraBlue,
        options: {
          darkModeSelector: '.dark-mode',
        },
      },
      translation: {
        firstDayOfWeek: 1,
      },
    }),
    MessageService,
  ],
};
