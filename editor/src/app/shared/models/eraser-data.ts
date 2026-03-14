import { MutationService } from '@/shared/interfaces/mutation-service';
import { Entity } from '@/shared/models/entity';
import { EntityMessages } from '@/shared/models/entity-messages';

export class EraserData<T extends Entity> {
  constructor(
    public entity: T,
    public name: string,
    public service: MutationService<T>,
    public messages: EntityMessages,
  ) {}

  getDetail(): string {
    return this.messages.format(this.name);
  }
}
