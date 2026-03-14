import { Entity } from '@/shared/models/entity';
import { EntityMessages } from '@/shared/models/entity-messages';
import { MutationService } from '@/shared/interfaces/mutation-service';

export class StorerData<T extends Entity> {
  constructor(
    public entity: T,
    public service: MutationService<T>,
    public messages: EntityMessages,
    public stayOnPage: boolean = false,
  ) {}
}
