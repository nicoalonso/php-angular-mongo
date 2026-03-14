import {
  ChangeDetectionStrategy,
  Component,
  inject,
  OnInit,
  signal,
} from '@angular/core';
import { NgOptimizedImage } from '@angular/common';
import { ActivatedRoute, RouterLink } from '@angular/router';
// Framework
import { Button } from 'primeng/button';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';

@Component({
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [NgOptimizedImage, Button, FaIconComponent, RouterLink],
  templateUrl: './not-found.page.html',
  styleUrl: './not-found.page.less',
})
export default class NotFoundPage implements OnInit {
  private readonly activeRouter = inject(ActivatedRoute);

  retryUrl = signal('');

  ngOnInit() {
    this.activeRouter.queryParams.subscribe(({ url = '' }) => {
      this.retryUrl.set(url);
    });
  }
}
