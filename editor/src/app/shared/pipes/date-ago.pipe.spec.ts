import { DateAgoPipe } from '@/shared/pipes/date-ago.pipe';

describe('DateAgoPipe', () => {
  it('should empty when value is undefined', () => {
    const pipe = new DateAgoPipe();

    expect(pipe.transform(undefined)).toBe('');
  });

  it('should return "ahora mismo" when value is less than 29 seconds ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const secondsAgo = 10;
    const pastDate = new Date(now.getTime() - secondsAgo * 1000);

    expect(pipe.transform(pastDate)).toBe('ahora mismo');
  });

  it('should return "hace 1 minuto" when value is 1 minute ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const minutesAgo = 1;
    const pastDate = new Date(now.getTime() - minutesAgo * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 1 minuto');
  });

  it('should return "hace 2 minutos" when value is 2 minutes ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const minutesAgo = 2;
    const pastDate = new Date(now.getTime() - minutesAgo * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 2 minutos');
  });

  it('should return "hace 1 hora" when value is 1 hour ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const hoursAgo = 1;
    const pastDate = new Date(now.getTime() - hoursAgo * 60 * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 1 hora');
  });

  it('should return "hace 2 horas" when value is 2 hours ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const hoursAgo = 2;
    const pastDate = new Date(now.getTime() - hoursAgo * 60 * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 2 horas');
  });

  it('should return "hace 1 día" when value is 1 day ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const daysAgo = 1;
    const pastDate = new Date(now.getTime() - daysAgo * 24 * 60 * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 1 día');
  });

  it('should return "hace 2 días" when value is 2 days ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const daysAgo = 2;
    const pastDate = new Date(now.getTime() - daysAgo * 24 * 60 * 60 * 1000);

    expect(pipe.transform(pastDate)).toBe('hace 2 días');
  });

  it('should return "hace 1 semana" when value is 1 week ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const weeksAgo = 1;
    const pastDate = new Date(
      now.getTime() - weeksAgo * 7 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 1 semana');
  });

  it('should return "hace 2 semanas" when value is 2 weeks ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const weeksAgo = 2;
    const pastDate = new Date(
      now.getTime() - weeksAgo * 7 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 2 semanas');
  });

  it('should return "hace 1 mes" when value is 1 month ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const monthsAgo = 1;
    const pastDate = new Date(
      now.getTime() - monthsAgo * 30 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 1 mes');
  });

  it('should return "hace 2 meses" when value is 2 months ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const monthsAgo = 2;
    const pastDate = new Date(
      now.getTime() - monthsAgo * 30 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 2 meses');
  });

  it('should return "hace 1 año" when value is 1 year ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const yearsAgo = 1;
    const pastDate = new Date(
      now.getTime() - yearsAgo * 365 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 1 año');
  });

  it('should return "hace 2 años" when value is 2 years ago', () => {
    const pipe = new DateAgoPipe();
    const now = new Date();
    const yearsAgo = 2;
    const pastDate = new Date(
      now.getTime() - yearsAgo * 365 * 24 * 60 * 60 * 1000,
    );

    expect(pipe.transform(pastDate)).toBe('hace 2 años');
  });
});
