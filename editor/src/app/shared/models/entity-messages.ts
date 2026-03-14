export class EntityMessages {
  constructor(
    public readonly title: string,
    public readonly summary: string,
    public readonly detail: string,
    public readonly error: string,
    public readonly notFound: string,
  ) {}

  public static create(name: string, genre: NounGenre): EntityMessages {
    const lexicon = new NounLexicon(genre);
    const notFound = `No se ha encontrado ${lexicon.article} ${name}`;

    const title = lexicon.capitalize(`${lexicon.newNoun} ${name}`);
    const summary = `${name} ${lexicon.createAdverb}`;
    const detail = lexicon.capitalize(
      `${lexicon.article} ${name} ha sido ${lexicon.createAdverb} correctamente`,
    );
    const error = `Error al crear ${lexicon.article} ${name}`;

    return new EntityMessages(title, summary, detail, error, notFound);
  }

  public static edit(name: string, genre: NounGenre): EntityMessages {
    const lexicon = new NounLexicon(genre);
    const notFound = `No se ha encontrado ${lexicon.article} ${name}`;

    const title = `${lexicon.editNoun} ${name}`;
    const summary = `${name} ${lexicon.updateAdverb}`;
    const detail = lexicon.capitalize(
      `${lexicon.article} ${name} ha sido ${lexicon.updateAdverb} correctamente`,
    );
    const error = `Error al actualizar ${lexicon.article} ${name}`;

    return new EntityMessages(title, summary, detail, error, notFound);
  }

  public static delete(name: string, genre: NounGenre): EntityMessages {
    const lexicon = new NounLexicon(genre);
    const notFound = `No se ha encontrado ${lexicon.article} ${name}`;

    const title = lexicon.capitalize(`${lexicon.deleteNoun} ${name}`);
    const summary = `${name} ${lexicon.deleteAdverb}`;
    const detail = 'Se ha eliminado "--item--"';
    const error = `Error al borrar ${lexicon.article} ${name}`;

    return new EntityMessages(title, summary, detail, error, notFound);
  }

  public static restore(name: string, genre: NounGenre): EntityMessages {
    const lexicon = new NounLexicon(genre);
    const notFound = `No se ha encontrado ${lexicon.article} ${name}`;

    const title = lexicon.capitalize(`${lexicon.restoreNoun} ${name}`);
    const summary = `${name} ${lexicon.restoreAdverb}`;
    const detail = 'Se ha restaurado "--item--"';
    const error = `Error al restaurar ${lexicon.article} ${name}`;

    return new EntityMessages(title, summary, detail, error, notFound);
  }

  format(item: string): string {
    return this.detail.replace('--item--', item);
  }
}

export enum NounGenre {
  male = 'male',
  female = 'female',
}

class NounLexicon {
  constructor(private readonly genre: NounGenre) {}

  get article(): string {
    return this.genre === NounGenre.male ? 'el' : 'la';
  }

  get newNoun(): string {
    return this.genre === NounGenre.male ? 'nuevo' : 'nueva';
  }

  get editNoun(): string {
    return 'editar';
  }

  get deleteNoun(): string {
    return 'eliminar';
  }

  get restoreNoun(): string {
    return 'restaurar';
  }

  get createAdverb(): string {
    return this.genre === NounGenre.male ? 'creado' : 'creada';
  }

  get updateAdverb(): string {
    return this.genre === NounGenre.male ? 'actualizado' : 'actualizada';
  }

  get deleteAdverb(): string {
    return this.genre === NounGenre.male ? 'borrado' : 'borrada';
  }

  get restoreAdverb(): string {
    return this.genre === NounGenre.male ? 'restaurado' : 'restaurada';
  }

  capitalize(name: string): string {
    return name.charAt(0).toUpperCase() + name.slice(1);
  }
}
