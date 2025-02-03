@extends('layouts.app')

@section('title', 'Centros Médicos')

@section('content')
<div class="max-w-4xl mx-auto py-4 sm:px-4 lg:px-6">
    <h2 class="font-semibold text-2xl text-indigo-600 dark:text-indigo-400 leading-tight mt-3 mb-6">
        {{ __('Centros Médicos') }}
    </h2>

    <div class="mb-8 p-6 border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 rounded-lg">
        <!-- Botón Agregar Centro Médico -->
        <div class="mb-4">
            <button onclick="openModal()" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg">
                Agregar Centro Médico
            </button>
        </div>

        <!-- Mensaje si no hay centros médicos registrados -->
        <div id="noCentrosMessage" class="{{ isset($centros) && count($centros) > 0 ? 'hidden' : '' }} text-center text-gray-600 dark:text-gray-300">
            <p>No hay centros médicos registrados.</p>
        </div>

        <!-- Tabla de centros médicos -->
        <div id="centrosTableContainer" class="{{ isset($centros) && count($centros) > 0 ? '' : 'hidden' }} ">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Dirección</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">RUC</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Estado</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Acciones</th>
                    </tr>
                </thead>
                <tbody id="centros-body">
                    @foreach($centros as $centro)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $centro->nombre }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $centro->direccion }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $centro->ruc }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $centro->estado == 'activo' ? 'Activo' : 'Inactivo' }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                            <button onclick="openEditModal({{ $centro->id }})" class="text-indigo-500 hover:text-indigo-600">Editar</button>
                            <button class="text-red-500 hover:text-red-600 btn-delete" data-id="{{ $centro->id }}">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar centro médico -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
            <h3 class="text-2xl text-gray-800 dark:text-gray-100 mb-4">Agregar Centro Médico</h3>
            <form id="addCentroForm">
                @csrf
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-800 dark:text-gray-200">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="direccion" class="block text-gray-800 dark:text-gray-200">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="ruc" class="block text-gray-800 dark:text-gray-200">RUC</label>
                    <input type="text" id="ruc" name="ruc" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="color_tema" class="block text-gray-800 dark:text-gray-200">Color Tema</label>
                    <select id="color_tema" name="color_tema" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="#ff0000" style="background-color: #ff0000;">Rojo</option>
                        <option value="#00ff00" style="background-color: #00ff00;">Verde</option>
                        <option value="#0000ff" style="background-color: #0000ff;">Azul</option>
                        <option value="#ffff00" style="background-color: #ffff00;">Amarillo</option>
                        <option value="#ff00ff" style="background-color: #ff00ff;">Rosa</option>
                        <option value="#00ffff" style="background-color: #00ffff;">Cian</option>
                        <option value="#ffffff" style="background-color: #ffffff;">Blanco</option>
                        <option value="#000000" style="background-color: #000000;">Negro</option>
                        <option value="#808080" style="background-color: #808080;">Gris</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="estado" class="block text-gray-800 dark:text-gray-200">Estado</label>
                    <select id="estado" name="estado" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
            <h3 class="text-2xl text-gray-800 dark:text-gray-100 mb-4">Editar Centro Médico</h3>
            <form id="editCentroForm">
                @csrf
                @method('PUT') <!-- Indicamos que la petición será PUT -->
                <input type="hidden" id="editId" name="id">
                <div class="mb-4">
                    <label for="editNombre" class="block text-gray-800 dark:text-gray-200">Nombre</label>
                    <input type="text" id="editNombre" name="nombre" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="editDireccion" class="block text-gray-800 dark:text-gray-200">Dirección</label>
                    <input type="text" id="editDireccion" name="direccion" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="editRuc" class="block text-gray-800 dark:text-gray-200">RUC</label>
                    <input type="text" id="editRuc" name="ruc" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label for="editColorTema" class="block text-gray-800 dark:text-gray-200">Color Tema</label>
                    <select id="editColorTema" name="color_tema" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="#ff0000" style="background-color: #ff0000;">Rojo</option>
                        <option value="#00ff00" style="background-color: #00ff00;">Verde</option>
                        <option value="#0000ff" style="background-color: #0000ff;">Azul</option>
                        <option value="#ffff00" style="background-color: #ffff00;">Amarillo</option>
                        <option value="#ff00ff" style="background-color: #ff00ff;">Rosa</option>
                        <option value="#00ffff" style="background-color: #00ffff;">Cian</option>
                        <option value="#ffffff" style="background-color: #ffffff;">Blanco</option>
                        <option value="#000000" style="background-color: #000000;">Negro</option>
                        <option value="#808080" style="background-color: #808080;">Gris</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editEstado" class="block text-gray-800 dark:text-gray-200">Estado</label>
                    <select id="editEstado" name="estado" class="mt-1 block w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    // Función para abrir el modal
    function openModal() {
        console.log('Abriendo el modal de agregar centro médico');
        document.getElementById('modal').classList.remove('hidden');
    }

    // Función para cerrar el modal
    function closeModal() {
        console.log('Cerrando el modal de agregar centro médico');
        document.getElementById('modal').classList.add('hidden');
    }

    function openEditModal(centroId) {
        console.log(`Abriendo el modal de edición para el centro médico con ID: ${centroId}`);
        fetch(`/centros/${centroId}`)
            .then(response => response.json())
            .then(data => {
                if (data.centros) {
                    console.log('Datos del centro médico cargados: ', data.centros);
                    // Rellenar el formulario con los datos del centro médico
                    document.getElementById('editId').value = data.centros.id;
                    document.getElementById('editNombre').value = data.centros.nombre;
                    document.getElementById('editDireccion').value = data.centros.direccion;
                    document.getElementById('editRuc').value = data.centros.ruc;
                    document.getElementById('editColorTema').value = data.centros.color_tema;
                    document.getElementById('editEstado').value = data.centros.estado;

                    // Abrir el modal de edición
                    document.getElementById('editModal').classList.remove('hidden');
                } else {
                    console.error("No se encontró el centro médico");
                }
            })
            .catch(error => console.error('Error al cargar los datos del centro:', error));
    }

    // Función para cerrar el modal de edición
    function closeEditModal() {
        console.log('Cerrando el modal de edición');
        document.getElementById('editModal').classList.add('hidden');
    }

    // Evento para enviar el formulario del centro médico
    document.getElementById('addCentroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log('Enviando formulario de nuevo centro médico con datos: ', formData);

        fetch("{{ route('centros.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Centro médico agregado exitosamente:', data.centro);
                    const centrosBody = document.getElementById('centros-body');
                    const centrosTableContainer = document.getElementById('centrosTableContainer');
                    const noCentrosMessage = document.getElementById('noCentrosMessage');

                    // Agregar el nuevo centro
                    const newCentroRow = `
                <tr class="bg-white dark:bg-gray-800">
                    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">${data.centro.nombre}</td>
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">${data.centro.direccion}</td>
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">${data.centro.ruc}</td>
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">${data.centro.estado == 'activo' ? 'Activo' : 'Inactivo'}</td>
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                        <button class="text-indigo-500 hover:text-indigo-600">Editar</button>
                        <button class="text-red-500 hover:text-red-600 btn-delete" data-id="${data.centro.id}">Eliminar</button>
                    </td>
                </tr>
                `;
                    centrosBody.insertAdjacentHTML('beforeend', newCentroRow);

                    // Mostrar la tabla y ocultar el mensaje
                    centrosTableContainer.classList.remove('hidden');
                    noCentrosMessage.classList.add('hidden');

                    // Cerrar el modal
                    closeModal();
                } else {
                    console.error('Error al agregar el centro médico.');
                    alert('Error al agregar el centro médico.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    document.getElementById('editCentroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const centroId = document.getElementById('editId').value;
        console.log(`Enviando actualización para el centro médico con ID: ${centroId}`);

        fetch(`/centros/${centroId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Centro médico actualizado:', data.centro);
                    // Actualizar la fila de la tabla
                    const centroRow = document.querySelector(`button[data-id="${centroId}"]`).closest('tr');
                    centroRow.cells[0].textContent = data.centro.nombre;
                    centroRow.cells[1].textContent = data.centro.direccion;
                    centroRow.cells[2].textContent = data.centro.ruc;
                    centroRow.cells[3].textContent = data.centro.estado == 'activo' ? 'Activo' : 'Inactivo';

                    // Cerrar el modal de edición
                    closeEditModal();
                } else {
                    console.error('Error al actualizar el centro médico.');
                    alert('Error al actualizar el centro médico.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Función para eliminar un centro médico
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        console.log('Esperando click en botones de eliminar...');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const centroId = this.getAttribute('data-id');
                console.log(`Botón de eliminar clickeado para el centro con ID: ${centroId}`);
                const confirmation = confirm('¿Estás seguro de que deseas eliminar este centro médico?');

                if (confirmation) {
                    fetch(`/centros/${centroId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(`Centro médico con ID: ${centroId} eliminado exitosamente`);
                                alert(data.message);
                                this.closest('tr').remove();
                                if (document.querySelectorAll('#centros-body tr').length === 0) {
                                    document.getElementById('noCentrosMessage').classList.remove('hidden');
                                }
                            } else {
                                console.error('Error: ', data.message);
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Hubo un problema al intentar eliminar el centro médico.');
                        });
                }
            });
        });
    });
</script>
@endsection