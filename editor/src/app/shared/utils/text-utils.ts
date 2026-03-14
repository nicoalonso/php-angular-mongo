export const shortText = (text: string = '', length: number): string => {
  return text.length > length ? text.substring(0, length) + '...' : text;
};

export const shortAuthor = (text = 'system'): string => {
  const atIndex = text.indexOf('@');
  if (atIndex === -1) {
    return text;
  }

  return text.substring(0, atIndex) + '...';
};
