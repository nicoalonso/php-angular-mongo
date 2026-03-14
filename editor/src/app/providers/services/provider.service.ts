import { Injectable } from '@angular/core';
// Models
import { Provider } from '@/providers/model/provider';
// Services
import { MutationService } from '@/shared/interfaces/mutation-service';

@Injectable({
  providedIn: 'root',
})
export class ProviderService extends MutationService<Provider> {
  private readonly resource = 'providers';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Provider): Provider {
    return Provider.from(item);
  }
}
