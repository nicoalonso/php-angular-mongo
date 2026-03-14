import { Entity } from '@/shared/models/entity';
import { ContactInfo } from '@/customers/model/contact-info';
import { Address } from '@/shared/models/address';
import { Membership } from '@/customers/model/membership';

export class Customer extends Entity {
  public readonly fullName: string;

  constructor(
    id: string,
    public name: string,
    public surname: string,
    public membership: Membership,
    public contact: ContactInfo,
    public address: Address,
    public vatNumber: string,
  ) {
    super(id);

    if (surname) {
      this.fullName = `${name} ${surname}`;
    } else {
      this.fullName = name;
    }
  }

  public static from(item: Customer): Customer {
    const client = new Customer(
      item.id,
      item.name,
      item.surname,
      Membership.from(item.membership),
      ContactInfo.from(item.contact),
      Address.from(item.address),
      item.vatNumber,
    );

    client.parse(item);
    return client;
  }
}
