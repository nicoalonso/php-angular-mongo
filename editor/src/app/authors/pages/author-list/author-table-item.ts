import { ListColumn } from '@/shared/models/list-column';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { Author } from '@/authors/model/author';
import { shortText } from '@/shared/utils/text-utils';

export const authorColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('name', 'Nombre'),
  ListColumn.text('realName', 'Nombre Real'),
  ListColumn.text('nationality', 'Nacionalidad'),
  ListColumn.date('createdAt', 'Creado en'),
];

interface AuthorTableItem extends TableIdentifier {
  id: string;
  name: string;
  realName: string;
  nationality: string;
  createdAt: Date;
}

const authorTableAdapter: AdapterFnc<Author, AuthorTableItem> = (
  value: Author,
): AuthorTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  name: value.name,
  realName: value.realName,
  nationality: value.nationality,
  createdAt: value.createdAt,
});

export const authorAdapter = authorTableAdapter as AdapterFnc;
