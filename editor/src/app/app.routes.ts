import { Routes } from '@angular/router';
// Pages
import HomePage from '@/main/pages/home/home.page';
import NotFoundPage from '@/main/pages/not-found/not-found.page';
import UnauthorizedPage from '@/main/pages/unauthorized/unauthorized.page';
// Guards
import { authGuard } from '@/shared/guards/auth.guard';
// Resolvers
import { customerResolver } from '@/customers/resolvers/customer.resolver';
import { authorResolver } from '@/authors/resolvers/author.resolver';
import { editorialResolver } from '@/editorials/resolvers/editorial.resolver';
import { bookResolver } from '@/books/resolvers/book.resolver';
import { providerResolver } from '@/providers/resolves/provider.resolver';
import { purchaseResolver } from '@/purchase/resolvers/purchase.resolver';
import { sequenceSaleResolver } from '@/sales/resolvers/sequence-sale.resolver';
import { saleResolver } from '@/sales/resolvers/sale.resolver';
import { sequenceBorrowResolver } from '@/borrows/resolvers/sequence-borrow.resolver';
import { borrowResolver } from '@/borrows/resolvers/borrow.resolver';
import { customerBorrowResolver } from '@/customers/resolvers/customer-borrow.resolver';

export const routes: Routes = [
  {
    path: '',
    component: HomePage,
    canActivate: [authGuard],
  },
  {
    path: 'customers',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/customers/pages/customer-list/customer-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/customers/pages/customer-create/customer-create.page'),
      },
      {
        path: 'edit/:id',
        loadComponent: () =>
          import('@/customers/pages/customer-edit/customer-edit.page'),
        resolve: {
          customer: customerResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/customers/pages/customer-view/customer-view.page'),
        resolve: {
          customer: customerResolver,
          borrows: customerBorrowResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'borrows',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/borrows/pages/borrow-list/borrow-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/borrows/pages/borrow-create/borrow-create.page'),
        resolve: {
          sequenceNumber: sequenceBorrowResolver,
        },
      },
      {
        path: 'checkin/:id',
        loadComponent: () =>
          import('@/borrows/pages/borrow-checkin/borrow-checkin.page'),
        resolve: {
          borrow: borrowResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/borrows/pages/borrow-view/borrow-view.page'),
        resolve: {
          borrow: borrowResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'sales',
    children: [
      {
        path: '',
        loadComponent: () => import('@/sales/pages/sale-list/sale-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/sales/pages/sale-create/sale-create.page'),
        resolve: {
          sequenceNumber: sequenceSaleResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () => import('@/sales/pages/sale-view/sale-view.page'),
        resolve: {
          sale: saleResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'authors',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/authors/pages/author-list/author-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/authors/pages/author-create/author-create.page'),
      },
      {
        path: 'edit/:id',
        loadComponent: () =>
          import('@/authors/pages/author-edit/author-edit.page'),
        resolve: {
          author: authorResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/authors/pages/author-view/author-view.page'),
        resolve: {
          author: authorResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'editorials',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/editorials/pages/editorial-list/editorial-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/editorials/pages/editorial-create/editorial-create.page'),
      },
      {
        path: 'edit/:id',
        loadComponent: () =>
          import('@/editorials/pages/editorial-edit/editorial-edit.page'),
        resolve: {
          editorial: editorialResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/editorials/pages/editorial-view/editorial-view.page'),
        resolve: {
          editorial: editorialResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'books',
    children: [
      {
        path: '',
        loadComponent: () => import('@/books/pages/book-list/book-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/books/pages/book-create/book-create.page'),
      },
      {
        path: 'edit/:id',
        loadComponent: () => import('@/books/pages/book-edit/book-edit.page'),
        resolve: {
          book: bookResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () => import('@/books/pages/book-view/book-view.page'),
        resolve: {
          book: bookResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'providers',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/providers/pages/provider-list/provider-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/providers/pages/provider-create/provider-create.page'),
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/providers/pages/provider-view/provider-view.page'),
        resolve: {
          provider: providerResolver,
        },
      },
      {
        path: 'edit/:id',
        loadComponent: () =>
          import('@/providers/pages/provider-edit/provider-edit.page'),
        resolve: {
          provider: providerResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'purchases',
    children: [
      {
        path: '',
        loadComponent: () =>
          import('@/purchase/pages/purchase-list/purchase-list.page'),
      },
      {
        path: 'create',
        loadComponent: () =>
          import('@/purchase/pages/purchase-create/purchase-create.page'),
      },
      {
        path: 'edit/:id',
        loadComponent: () =>
          import('@/purchase/pages/purchase-edit/purchase-edit.page'),
        resolve: {
          purchase: purchaseResolver,
        },
      },
      {
        path: ':id',
        loadComponent: () =>
          import('@/purchase/pages/purchase-view/purchase-view.page'),
        resolve: {
          purchase: purchaseResolver,
        },
      },
    ],
    canActivate: [authGuard],
  },
  {
    path: 'unauthorized',
    component: UnauthorizedPage,
  },
  {
    path: 'not-found',
    component: NotFoundPage,
  },
  {
    path: '**',
    component: NotFoundPage,
  },
];
