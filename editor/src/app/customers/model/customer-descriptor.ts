export class CustomerDescriptor {
  public readonly fullName: string;

  constructor(
    public id: string,
    public name: string,
    public surname: string,
    public vatNumber: string,
    public number: string,
  ) {
    if (surname) {
      this.fullName = `${name} ${surname}`;
    } else {
      this.fullName = name;
    }
  }

  public static from(item: CustomerDescriptor): CustomerDescriptor {
    return new CustomerDescriptor(
      item.id,
      item.name,
      item.surname,
      item.vatNumber,
      item.number,
    );
  }
}
