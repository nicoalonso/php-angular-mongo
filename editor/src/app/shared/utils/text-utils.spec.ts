import { shortAuthor, shortText } from '@/shared/utils/text-utils';

describe('TextUtils', () => {
  it('should truncate text correctly', () => {
    const text = 'This is a long text that needs to be truncated.';
    const truncated = shortText(text, 10);

    expect(truncated).toBe('This is a ...');
  });

  it('should not truncate short text', () => {
    const text = 'Short text';
    const truncated = shortText(text, 20);

    expect(truncated).toBe(text);
  });

  it('should truncate author correctly', () => {
    const author = 'johndoe@gmail.com';
    const truncated = shortAuthor(author);

    expect(truncated).toBe('johndoe...');
  });

  it('should not truncate when wrong author', () => {
    const author = 'johndoe';
    const truncated = shortAuthor(author);

    expect(truncated).toBe('johndoe');
  });
});
