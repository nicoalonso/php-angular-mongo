import { Injectable } from '@angular/core';
// Services
import { MutationService } from '@/shared/interfaces/mutation-service';
// Models
import { Author } from '@/authors/model/author';

@Injectable({
  providedIn: 'root',
})
export class AuthorService extends MutationService<Author> {
  private readonly resource = 'authors';

  protected override getResource(): string {
    return this.resource;
  }

  protected override makeItem(item: Author): Author {
    return Author.from(item);
  }
}
