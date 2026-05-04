import { DefaultValuePipe } from '@/shared/pipes/default-value.pipe';

describe('DefaultValuePipe', () => {
  it('should return default value when value is undefined', () => {
    const pipe = new DefaultValuePipe();

    expect(pipe.transform(undefined)).toBe('--');
  });

  it('should return default value when value is null', () => {
    const pipe = new DefaultValuePipe();

    expect(pipe.transform(null)).toBe('--');
  });

  it('should return default value when value is empty string', () => {
    const pipe = new DefaultValuePipe();

    expect(pipe.transform('')).toBe('--');
  });

  it('should return provided default value when value is undefined', () => {
    const pipe = new DefaultValuePipe();

    expect(pipe.transform(undefined, 'N/A')).toBe('N/A');
  });

  it('should return original value when value is valid', () => {
    const pipe = new DefaultValuePipe();

    expect(pipe.transform('Hello')).toBe('Hello');
  });
});
