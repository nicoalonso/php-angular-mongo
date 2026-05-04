import { findOptionLabel, SelectOption } from '@/shared/models/select-option';

describe('SelectOption', () => {
  it('should run when create', () => {
    const option = new SelectOption('Label', 'Value');

    expect(option.label).toBe('Label');
    expect(option.value).toBe('Value');
  });

  it('should find option label', () => {
    const options: SelectOption[] = [
      {
        label: 'En préstamo',
        value: 'false',
      },
      {
        label: 'Retornado',
        value: 'true',
      },
    ];

    expect(findOptionLabel(options, 'false')).toBe('En préstamo');
  });
});
