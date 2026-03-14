import { Entity } from '@/shared/models/entity';

export class Author extends Entity {
  constructor(
    id: string,
    public name: string,
    public realName: string,
    public genres: string,
    public biography: string,
    public nationality: string,
    public birthDate: Date,
    public deathDate: Date | null,
    public photoUrl: string,
    public website: string,
  ) {
    super(id);
  }

  public static from(item: Author): Author {
    const author = new Author(
      item.id,
      item.name,
      item.realName,
      item.genres,
      item.biography,
      item.nationality,
      new Date(item.birthDate),
      item.deathDate ? new Date(item.deathDate) : null,
      item.photoUrl,
      item.website,
    );

    author.parse(item);
    return author;
  }
}
