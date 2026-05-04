import { ComponentFixture } from '@angular/core/testing';

export const findById = <T extends HTMLElement = HTMLElement>(
  fixture: ComponentFixture<any>,
  id: string,
): T => {
  const element = (fixture.nativeElement as HTMLElement).querySelector(
    `#${id}`,
  );
  if (!element) {
    throw new Error(`Element with id "${id}" not found.`);
  }

  return element as T;
};

export const findOneByContent = <T extends HTMLElement = HTMLElement>(
  fixture: ComponentFixture<any>,
  selector: string,
  content: string,
): T => {
  const found = findByQuery<T>(fixture, selector).find((element) =>
    element.textContent?.includes(content),
  );
  if (!found) {
    throw new Error(
      `Element with selector "${selector}" and content "${content}" not found.`,
    );
  }

  return found;
};

export const findOneByAttribute = <T extends HTMLElement = HTMLElement>(
  fixture: ComponentFixture<any>,
  selector: string,
  attribute: string,
  value: string,
): T => {
  const found = findByQuery<T>(fixture, selector).find((element) =>
    element.getAttribute(attribute)?.includes(value),
  );
  if (!found) {
    throw new Error(
      `Element with selector "${selector}" and attribute "${attribute}"="${value}" not found.`,
    );
  }

  return found;
};

export const findByQuery = <T extends HTMLElement = HTMLElement>(
  fixture: ComponentFixture<any>,
  selector: string,
): T[] => {
  const elements = (fixture.nativeElement as HTMLElement).querySelectorAll(
    selector,
  );
  return Array.from(elements) as T[];
};
