import { MenuItem } from 'primeng/api';
import { User } from '@/shared/models/user';

export const MAIN_MENU: MenuItem[] = [
  {
    label: 'Trámites',
    role: 'user',
    items: [
      {
        label: 'Nuevo Préstamo',
        icon: 'fas fa-briefcase',
        routerLink: '/borrows/create',
      },
      {
        label: 'Listado de préstamos',
        icon: 'fas fa-briefcase',
        routerLink: '/borrows',
      },
      {
        label: 'Nueva Venta',
        icon: 'fas fa-cash-register',
        routerLink: '/sales/create',
      },
      {
        label: 'Listado de ventas',
        icon: 'fas fa-cash-register',
        routerLink: '/sales',
      },
    ],
  },
  {
    label: 'Catálogo',
    icon: 'fas fa-database',
    role: 'user',
    items: [
      {
        label: 'Listado de libros',
        icon: 'fas fa-book',
        routerLink: '/books',
      },
      {
        label: 'Listado de autores',
        icon: 'fas fa-pen-fancy',
        routerLink: '/authors',
      },
      {
        label: 'Listado de Editoriales',
        icon: 'fas fa-stamp',
        routerLink: '/editorials',
      },
    ],
  },
  {
    label: 'Clientes',
    items: [
      {
        label: 'Listado de clientes',
        icon: 'fas fa-people-group',
        routerLink: '/customers',
      },
      {
        label: 'Nuevo cliente',
        icon: 'fas fa-person-circle-plus',
        routerLink: '/customers/create',
      },
    ],
    role: 'user',
  },
  {
    label: 'Compras',
    items: [
      {
        label: 'Listado de entradas',
        icon: 'fas fa-warehouse',
        routerLink: '/purchases',
      },
      {
        label: 'Listado de proveedores',
        icon: 'fas fa-truck',
        routerLink: '/suppliers',
      },
      {
        label: 'Nueva entrada',
        icon: 'fas fa-box-open',
        routerLink: '/purchases/create',
      },
    ],
  },
];

export const getMainMenuByUser = (user: User): MenuItem[] => {
  return filterMenuItems(MAIN_MENU, user);
};

export const filterMenuItems = (
  elements: MenuItem[],
  user: User,
): MenuItem[] => {
  return elements.filter((element) => {
    const { role = '' } = element;
    const valid = user.checkRole(role);

    if (valid && element.items) {
      element.items = filterMenuItems(element.items, user);
    }

    return valid;
  });
};
