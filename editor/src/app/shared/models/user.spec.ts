import { User } from '@/shared/models/user';

describe('User', () => {
  it('should create', () => {
    const user = new User('John Doe', 'John', ['admin']);

    expect(user).toBeTruthy();
    expect(user.name).toBe('John Doe');
    expect(user.displayName).toBe('John');
    expect(user.groups).toEqual(['admin']);
    expect(user.role).toBe('Administrador');
    expect(user.checkRole('admin')).toBeTruthy();
    expect(user.checkRole('user')).toBeTruthy();
    expect(user.isUser()).toBeTruthy();
    expect(user.isAdmin()).toBeTruthy();
  });

  it('should create as normal user', () => {
    const user = new User('John Doe', 'John', ['user']);

    expect(user).toBeTruthy();
    expect(user.name).toBe('John Doe');
    expect(user.displayName).toBe('John');
    expect(user.groups).toEqual(['user']);
    expect(user.role).toBe('Usuario');
    expect(user.checkRole('admin')).toBeFalsy();
    expect(user.checkRole('user')).toBeTruthy();
    expect(user.isUser()).toBeTruthy();
    expect(user.isAdmin()).toBeFalsy();
  });
});
