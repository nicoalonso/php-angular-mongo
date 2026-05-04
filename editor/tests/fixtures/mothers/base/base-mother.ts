import { isMotherMapping, MotherMapping } from './mother-mapping';
import { MotherBuild } from './mother-build';

type ItemValue =
  | string
  | number
  | boolean
  | Date
  | MotherMapping
  | MotherBuild
  | any[]
  | null;
export type Item = ItemValue | ItemValue[];

export abstract class BaseMother {
  protected static merge<T = object, U = T>(
    values: T,
    overrides?: Partial<U>,
  ): U {
    const fields: U = {} as U;

    for (const key of Object.keys(values as object)) {
      // @ts-ignore
      if (overrides && key in overrides && overrides[key] !== undefined) {
        // @ts-ignore
        fields[key] = overrides[key] as U[Extract<keyof U, string>];
        continue;
      }

      // @ts-ignore
      const item = values[key] as Item;
      const [value, mapping, builder] = BaseMother.getMapping(item);
      // @ts-ignore
      fields[key] = BaseMother.applyMapping(value, mapping, builder);
    }

    return fields;
  }

  protected static getMapping(
    item: Item,
  ): [ItemValue, MotherMapping, MotherBuild | null] {
    if (!Array.isArray(item)) {
      if (typeof item === 'function') {
        return [null, MotherMapping.MOTHER, new MotherBuild(item)];
      }
      if (typeof item === 'string' && isMotherMapping(item)) {
        return [null, item, null];
      }

      return [item, MotherMapping.NONE, null];
    }

    let value: ItemValue = null;
    let map = MotherMapping.NONE;
    let builder: MotherBuild | null = null;

    for (const element of item) {
      if (typeof element === 'string' && isMotherMapping(element)) {
        map = element;
      } else if (typeof element === 'function') {
        map = MotherMapping.MOTHER;
        builder = new MotherBuild(element);
      } else {
        value = element;
      }
    }

    if (null !== builder) {
      value = null;
    }

    return [value, map, builder];
  }

  protected static applyMapping(
    value: ItemValue,
    mapping: MotherMapping,
    builder: MotherBuild | null,
  ): ItemValue {
    if (mapping === MotherMapping.REQUIRED && !value) {
      throw new Error('Value is required');
    }

    if (
      mapping === MotherMapping.MOTHER &&
      null !== builder &&
      null === value
    ) {
      return builder.build() as ItemValue;
    }

    if (mapping === MotherMapping.ARRAY && Array.isArray(value)) {
      return value.map((item) => {
        if (typeof item === 'function') {
          return item();
        }
        return item;
      }) as ItemValue;
    }

    if (mapping === MotherMapping.DATE) {
      if (!value) {
        return new Date();
      }

      return new Date(value as string);
    }

    if (mapping === MotherMapping.UUID) {
      return crypto.randomUUID();
    }

    return value;
  }
}
