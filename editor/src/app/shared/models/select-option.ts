export class SelectOption {
  constructor(
    public label: string,
    public value: string,
  ) {}
}

export const findOptionLabel = (
  options: SelectOption[],
  value: string,
): string => {
  const option = options.find((opt) => opt.value === value);
  return option ? option.label : '';
};
