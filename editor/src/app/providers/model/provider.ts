import { Entity } from '@/shared/models/entity';
import { EnterpriseContact } from '@/shared/models/enterprise-contact';
import { Address } from '@/shared/models/address';

export class Provider extends Entity {
  constructor(
    id: string,
    public name: string,
    public comercialName: string,
    public contact: EnterpriseContact,
    public address: Address,
    public vatNumber: string,
  ) {
    super(id);
  }

  public static from(item: Provider): Provider {
    const provider = new Provider(
      item.id,
      item.name,
      item.comercialName,
      item.contact,
      item.address,
      item.vatNumber,
    );

    provider.parse(item);
    return provider;
  }
}
