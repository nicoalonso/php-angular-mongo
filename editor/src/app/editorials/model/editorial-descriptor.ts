export class EditorialDescriptor {
  constructor(
    public id: string,
    public name: string,
  ) {}

  public static from(item: EditorialDescriptor): EditorialDescriptor {
    return new EditorialDescriptor(item.id, item.name);
  }
}
