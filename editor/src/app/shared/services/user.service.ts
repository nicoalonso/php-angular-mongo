import { Injectable, signal } from '@angular/core';
import { Observable, of } from 'rxjs';
import { User } from '@/shared/models/user';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  #user = signal<User | null>(null);

  get user(): User | null {
    return this.#user();
  }

  isAuthenticated(): Observable<boolean> {
    if (!this.user) {
      let simulateUser = new User('john.doe@gmail.com', 'John Doe', ['admin']);
      this.#user.set(simulateUser);
    }

    return of(!!this.user);
  }
}
