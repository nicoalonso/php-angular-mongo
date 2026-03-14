export class ProviderDescriptor {
  constructor(
    public id: string,
    public name: string,
  ) {}

  public static from(item: ProviderDescriptor): ProviderDescriptor {
    return new ProviderDescriptor(item.id, item.name);
  }
}
