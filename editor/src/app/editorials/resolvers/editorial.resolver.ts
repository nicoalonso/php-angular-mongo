import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Editorial } from '@/editorials/model/editorial';
// Services
import { EditorialService } from '@/editorials/services/editorial.service';

export const editorialResolver: ResolveFn<Editorial> = (route) => {
  const editorialService = inject(EditorialService);
  const editorialId = route.paramMap.get('id')!;
  return editorialService.getItem(editorialId);
};
