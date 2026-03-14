import { Entity } from '@/shared/models/entity';
import { EntityMessages } from '@/shared/models/entity-messages';
import { MutationService } from '@/shared/interfaces/mutation-service';

export class CreatorData<T extends Entity> {
  constructor(
    public service: MutationService<T>,
    public messages: EntityMessages,
  ) {}
}
