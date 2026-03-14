export interface ListQuery {
  createdBy?: string;
  createdAt?: string;
  updatedBy?: string;
  updatedAt?: string;
  page?: number;
  limit?: number;
  sort?: string;
  [key: string]: string | number | boolean | undefined;
}
