import { Directive, inject, OnInit } from '@angular/core';
import { Router } from '@angular/router';
// Components framework
import { MenuItem } from 'primeng/api';
// Pages
import { AbstractCredentialPage } from '@/shared/pages/abstract-credential/abstract-credential.page';

@Directive()
export abstract class AbstractViewPage
  extends AbstractCredentialPage
  implements OnInit
{
  protected router: Router = inject(Router);

  homeBreadcrumb: MenuItem = { icon: 'fas fa-home', routerLink: '/' };

  ngOnInit() {
    this.userSubscribe();
  }

  protected goBack(): void {
    this.router.navigate(this.getPathBack()).then();
  }

  getPathBack(): string[] {
    return ['/'];
  }
}
