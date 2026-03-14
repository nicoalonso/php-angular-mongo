import { Directive, inject, OnInit, signal } from '@angular/core';
import { ActivatedRoute, Params, Router } from '@angular/router';
// Pages
import { AbstractCredentialPage } from '@/shared/pages/abstract-credential/abstract-credential.page';
// Models
import { ListConstraint } from '@/shared/models/list-constraint';
import { TableIdentifier } from '@/shared/components/list-table/list-table-types';

@Directive()
export abstract class AbstractListPage
  extends AbstractCredentialPage
  implements OnInit
{
  protected readonly createPath: string = 'create';

  protected activatedRoute: ActivatedRoute = inject(ActivatedRoute);
  protected router: Router = inject(Router);

  constraints = signal<ListConstraint[]>([]);

  ngOnInit() {
    this.userSubscribe();
    this.loadConstraints();
  }

  protected loadConstraints(): void {
    this.activatedRoute.queryParams.subscribe((params: Params) => {
      const constraints: ListConstraint[] = [];
      for (const param in params) {
        constraints.push(new ListConstraint(param, params[param]));
      }
      this.handleConstraints(constraints);
    });
  }

  protected handleConstraints(constraints: ListConstraint[]): void {
    this.constraints.set(constraints);
  }

  onCreate(): void {
    this.router.navigate([this.getPath(), this.createPath]).then();
  }

  onClick($event: unknown): void {
    const entity = $event as TableIdentifier;
    this.router.navigate([this.getPath(), entity._id]).then();
  }

  abstract getPath(): string;
}
