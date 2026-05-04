export enum MotherMapping {
  NONE = 'mm:none',
  UUID = 'mm:uuid',
  ARRAY = 'mm:array',
  DATE = 'mm:date',
  REQUIRED = 'mm:required',
  MOTHER = 'mm:mother',
}

export const isMotherMapping = (value: string): value is MotherMapping => {
  return Object.values(MotherMapping).includes(value as MotherMapping);
};
