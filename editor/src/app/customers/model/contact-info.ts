export class ContactInfo {
  constructor(
    public email: string,
    public phone1: string,
    public phone2: string,
  ) {}

  public static from(item: ContactInfo): ContactInfo {
    return new ContactInfo(item.email, item.phone1, item.phone2);
  }
}
