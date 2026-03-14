import { Directive, inject, signal } from '@angular/core';
import { UserService } from '@/shared/services/user.service';

@Directive()
export abstract class AbstractCredentialPage {
  protected userService: UserService = inject(UserService);

  isAdmin = signal<boolean>(false);
  isUser = signal<boolean>(false);

  protected userSubscribe(): void {
    this.userService.isAuthenticated().subscribe((isAuthenticated: boolean) => {
      if (isAuthenticated) {
        this.isAdmin.set(this.userService.user!.isAdmin());
        this.isUser.set(this.userService.user!.isUser());
      }
    });
  }
}
