import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { UserService } from '@/shared/services/user.service';
import { map } from 'rxjs';

export const authGuard: CanActivateFn = () => {
  const userService = inject(UserService);
  const router = inject(Router);

  return userService.isAuthenticated().pipe(
    map((isAuthenticated: boolean) => {
      if (!isAuthenticated) {
        return router.parseUrl('/unauthorized');
      }

      return isAuthenticated;
    }),
  );
};
