import { EntityMessages, NounGenre } from '@/shared/models/entity-messages';

describe('EntityMessages', () => {
  it('should create male messages', () => {
    const messages = EntityMessages.create('libro', NounGenre.male);

    expect(messages.title).toBe('Nuevo libro');
    expect(messages.summary).toBe('libro creado');
    expect(messages.detail).toBe('El libro ha sido creado correctamente');
    expect(messages.error).toBe('Error al crear el libro');
    expect(messages.notFound).toBe('No se ha encontrado el libro');
  });

  it('should create female messages', () => {
    const messages = EntityMessages.create('editorial', NounGenre.female);

    expect(messages.title).toBe('Nueva editorial');
    expect(messages.summary).toBe('editorial creada');
    expect(messages.detail).toBe('La editorial ha sido creada correctamente');
    expect(messages.error).toBe('Error al crear la editorial');
    expect(messages.notFound).toBe('No se ha encontrado la editorial');
  });

  it('should edit male messages', () => {
    const messages = EntityMessages.edit('libro', NounGenre.male);

    expect(messages.title).toBe('Editar libro');
    expect(messages.summary).toBe('libro actualizado');
    expect(messages.detail).toBe('El libro ha sido actualizado correctamente');
    expect(messages.error).toBe('Error al actualizar el libro');
  });

  it('should edit female messages', () => {
    const messages = EntityMessages.edit('editorial', NounGenre.female);

    expect(messages.title).toBe('Editar editorial');
    expect(messages.summary).toBe('editorial actualizada');
    expect(messages.detail).toBe(
      'La editorial ha sido actualizada correctamente',
    );
    expect(messages.error).toBe('Error al actualizar la editorial');
  });

  it('should delete male messages', () => {
    const messages = EntityMessages.delete('libro', NounGenre.male);

    expect(messages.title).toBe('Eliminar libro');
    expect(messages.summary).toBe('libro borrado');
    expect(messages.detail).toBe('Se ha eliminado "--item--"');
    expect(messages.format('12345')).toBe('Se ha eliminado "12345"');
    expect(messages.error).toBe('Error al borrar el libro');
  });

  it('should delete female messages', () => {
    const messages = EntityMessages.delete('editorial', NounGenre.female);

    expect(messages.title).toBe('Eliminar editorial');
    expect(messages.summary).toBe('editorial borrada');
    expect(messages.detail).toBe('Se ha eliminado "--item--"');
    expect(messages.error).toBe('Error al borrar la editorial');
  });

  it('should restore male messages', () => {
    const messages = EntityMessages.restore('libro', NounGenre.male);

    expect(messages.title).toBe('Restaurar libro');
    expect(messages.summary).toBe('libro restaurado');
    expect(messages.detail).toBe('Se ha restaurado "--item--"');
    expect(messages.error).toBe('Error al restaurar el libro');
  });

  it('should restore female messages', () => {
    const messages = EntityMessages.restore('editorial', NounGenre.female);

    expect(messages.title).toBe('Restaurar editorial');
    expect(messages.summary).toBe('editorial restaurada');
    expect(messages.detail).toBe('Se ha restaurado "--item--"');
    expect(messages.error).toBe('Error al restaurar la editorial');
  });
});
