export class User {
  private readonly adminRole: string = 'admin';

  constructor(
    public name: string,
    public displayName: string,
    public groups: string[],
  ) {}

  get role(): string {
    return this.isAdmin() ? 'Administrador' : 'Usuario';
  }

  checkRole(role: string): boolean {
    switch (role) {
      case this.adminRole:
        return this.isAdmin();
      default:
        return this.isUser();
    }
  }

  isUser(): boolean {
    return true;
  }

  isAdmin(): boolean {
    return this.groups.includes(this.adminRole);
  }
}
