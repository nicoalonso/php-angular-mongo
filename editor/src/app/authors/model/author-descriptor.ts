export class AuthorDescriptor {
  constructor(
    public id: string,
    public name: string,
  ) {}

  public static from(item: AuthorDescriptor): AuthorDescriptor {
    return new AuthorDescriptor(item.id, item.name);
  }
}
