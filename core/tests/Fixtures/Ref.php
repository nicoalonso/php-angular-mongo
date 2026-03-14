<?php declare(strict_types=1);

namespace App\Tests\Fixtures;

enum Ref: string
{
    // Authors
    case AuthorShakespeare = 'author:shakespeare';
    case AuthorCervantes = 'author:cervantes';
    // Books
    case BookRomeoAndJuliet = 'book:romeo-and-juliet';
    case BookDonQuijote = 'book:don-quijote';
    // Borrows
    case BorrowJohnDoe = 'borrow:john-doe';
    // BorrowLines
    case BorrowLineJohnRomeoAndJuliet = 'borrow-line:john-doe:romeo-and-juliet';
    case BorrowLineJohnQuijote = 'borrow-line:john-doe:quijote';
    // Customers
    case CustomerJohnDoe = 'customer:john-doe';
    // Editorials
    case EditorialAnaya = 'editorial:anaya';
    // Providers
    case ProviderAmazon = 'provider:amazon';
    case ProviderBestBuy = 'provider:best-buy';
    // Purchases
    case PurchaseAmazonInv1 = 'purchase:amazon:inv-1';
    case PurchaseBestBuyInv2 = 'purchase:best-buy:inv-2';
    // PurchaseLines
    case PurchaseLineAmazonLine1 = 'purchase-line:amazon:line1';
    case PurchaseLineAmazonLine2 = 'purchase-line:amazon:line2';
    case PurchaseLineBestBuyLine1 = 'purchase-line:best-buy:line1';
    // Sales
    case SaleJohnDoe1 = 'sale:john-doe:1';
    case SaleJohnDoe2 = 'sale:john-doe:2';
    // SaleLines
    case SaleLineJohnDoe1Line1 = 'sale-line:john-doe:1:line-1';
    case SaleLineJohnDoe1Line2 = 'sale-line:john-doe:1:line-2';
    case SaleLineJohnDoe2Line1 = 'sale-line:john-doe:2:line-1';
}
