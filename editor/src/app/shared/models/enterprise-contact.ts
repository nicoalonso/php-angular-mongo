export class EnterpriseContact {
  constructor(
    public email: string,
    public website: string,
    public phone1: string,
    public phone2: string,
  ) {}

  public static from(item: EnterpriseContact): EnterpriseContact {
    return new EnterpriseContact(
      item.email,
      item.website,
      item.phone1,
      item.phone2,
    );
  }
}
