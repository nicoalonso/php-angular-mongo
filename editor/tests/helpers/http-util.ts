import { environment } from '@environments/environment';

export const buildEndpoint = (resource: string, id?: string): string => {
  const baseEndpoint = environment.api.endpoint;

  return id
    ? `${baseEndpoint}/${resource}/${id}`
    : `${baseEndpoint}/${resource}`;
};

export const buildEndpointQueryString = (
  resource: string,
  query: object,
): string => {
  const baseEndpoint = environment.api.endpoint;
  const url = new URL(`${baseEndpoint}/${resource}`);

  Object.entries(query).forEach(([key, value]) => {
    if (value !== undefined && value !== null) {
      url.searchParams.append(key, String(value));
    }
  });

  return url.toString();
};
