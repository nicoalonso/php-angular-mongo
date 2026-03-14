import { Entity } from '@/shared/models/entity';
import { EnterpriseContact } from '@/shared/models/enterprise-contact';
import { Address } from '@/shared/models/address';

export class Editorial extends Entity {
  constructor(
    id: string,
    public name: string,
    public comercialName: string,
    public contact: EnterpriseContact,
    public address: Address,
  ) {
    super(id);
  }

  public static from(item: Editorial): Editorial {
    const editorial = new Editorial(
      item.id,
      item.name,
      item.comercialName,
      EnterpriseContact.from(item.contact),
      Address.from(item.address),
    );

    editorial.parse(item);
    return editorial;
  }
}
