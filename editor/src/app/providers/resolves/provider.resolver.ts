import { ResolveFn } from '@angular/router';
import { inject } from '@angular/core';
// Models
import { Provider } from '@/providers/model/provider';
// Services
import { ProviderService } from '@/providers/services/provider.service';

export const providerResolver: ResolveFn<Provider> = (route) => {
  const providerService = inject(ProviderService);
  const providerId = route.paramMap.get('id')!;
  return providerService.getItem(providerId);
};
