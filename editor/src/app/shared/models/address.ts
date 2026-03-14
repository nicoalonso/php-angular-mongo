export class Address {
  constructor(
    public street: string,
    public postalCode: string,
    public city: string,
    public province: string,
    public country: string,
  ) {}

  public static from(item: Address): Address {
    return new Address(
      item.street,
      item.postalCode,
      item.city,
      item.province,
      item.country,
    );
  }
}
