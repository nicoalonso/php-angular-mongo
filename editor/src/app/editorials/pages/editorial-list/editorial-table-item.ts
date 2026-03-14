import { ListColumn } from '@/shared/models/list-column';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { Editorial } from '@/editorials/model/editorial';
import { shortText } from '@/shared/utils/text-utils';

export const editorialColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('name', 'Nombre'),
  ListColumn.text('comercialName', 'Nombre comercial'),
  ListColumn.text('website', 'Web'),
  ListColumn.date('createdAt', 'Creado en'),
];

interface EditorialTableItem extends TableIdentifier {
  id: string;
  name: string;
  comercialName: string;
  website: string;
  createdAt: Date;
}

const editorialTableAdapter: AdapterFnc<Editorial, EditorialTableItem> = (
  value: Editorial,
): EditorialTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  name: value.name,
  comercialName: value.comercialName,
  website: value.contact.website,
  createdAt: value.createdAt,
});

export const editorialAdapter = editorialTableAdapter as AdapterFnc;
