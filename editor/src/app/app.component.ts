import {
  ChangeDetectionStrategy,
  Component,
  inject,
  OnInit,
} from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import {
  FaIconLibrary,
  FontAwesomeModule,
} from '@fortawesome/angular-fontawesome';
import { PrimeNG } from 'primeng/config';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
// Components
import { LayoutComponent } from '@/main/layout/layout/layout.component';

@Component({
  selector: 'app-root',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [FontAwesomeModule, LayoutComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.less',
})
export class AppComponent implements OnInit {
  private readonly defaultLanguage: string = 'es';

  private translateService: TranslateService = inject(TranslateService);
  private primeng: PrimeNG = inject(PrimeNG);
  private library = inject(FaIconLibrary);

  ngOnInit(): void {
    this.library.addIconPacks(fas, far);
    this.configTranslations(this.defaultLanguage);
  }

  configTranslations(lang: string): void {
    this.translateService.use(lang);
    this.translateService
      .get('primeng')
      .subscribe((res) => this.primeng.setTranslation(res));
  }
}
