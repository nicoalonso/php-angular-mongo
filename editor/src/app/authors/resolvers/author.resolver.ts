import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Author } from '@/authors/model/author';
// Services
import { AuthorService } from '@/authors/services/author.service';

export const authorResolver: ResolveFn<Author> = (route) => {
  const authorService = inject(AuthorService);
  const authorId = route.paramMap.get('id')!;
  return authorService.getItem(authorId);
};
