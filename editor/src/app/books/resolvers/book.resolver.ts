import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Book } from '@/books/model/book';
// Services
import { BookService } from '@/books/services/book-service';

export const bookResolver: ResolveFn<Book> = (route) => {
  const bookService = inject(BookService);
  const bookId = route.paramMap.get('id')!;
  return bookService.getItem(bookId);
};
