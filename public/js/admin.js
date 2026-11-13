let productos = [];

async function cargarProductos() {
    const respuesta = await fetch('api/productos');
    const resultado = await respuesta.json();

    productos = resultado.data;

    mostrarTablaAdmin(productos);
}

async function cargarCategorias() {
    const respuesta = await fetch('api/categorias');
    const resultado = await respuesta.json();

    const select = document.getElementById('id_categoria');

    resultado.data.forEach(categoria => {
        select.innerHTML += `<option value="${categoria.id_categoria}">${categoria.nombre}</option>`;
    });
}

function mostrarTablaAdmin(lista) {
    const tabla = document.getElementById('tabla-admin');
    tabla.innerHTML = '';

    lista.forEach(producto => {
        tabla.innerHTML += `
            <tr class="transition hover:bg-brand-50">
                <td class="p-3 font-medium text-slate-900">${producto.nombre} ${producto.presentacion}</td>
                <td class="p-3">${producto.categoria}</td>
                <td class="p-3">S/. ${producto.precio}</td>
                <td class="p-3">${producto.stock}</td>
                <td class="p-3 text-right whitespace-nowrap">
                    <button data-id="${producto.id_producto}" class="btn-editar rounded-lg bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 transition hover:bg-amber-200">Editar</button>
                    <button data-id="${producto.id_producto}" class="btn-eliminar rounded-lg bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 transition hover:bg-rose-200">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

document.getElementById('tabla-admin').addEventListener('click', async (evento) => {
    const boton = evento.target;

    if (boton.classList.contains('btn-eliminar')) {
        const id = boton.dataset.id;

        if (!confirm('¿Seguro que deseas eliminar este producto?')) {
            return;
        }

        const respuesta = await fetch(`api/productos/${id}`, {
            method: 'DELETE'
        });
        const resultado = await respuesta.json();

        alert(resultado.message);
        cargarProductos();
    }

    if (boton.classList.contains('btn-editar')) {
        const id = Number(boton.dataset.id);
        const producto = productos.find(p => p.id_producto === id);

        document.getElementById('id_producto').value = producto.id_producto;
        document.getElementById('nombre').value = producto.nombre;
        document.getElementById('presentacion').value = producto.presentacion;
        document.getElementById('id_categoria').value = producto.id_categoria;
        document.getElementById('precio').value = producto.precio;
        document.getElementById('stock').value = producto.stock;
        document.getElementById('descripcion').value = producto.descripcion ?? '';

        document.getElementById('titulo-form').textContent = 'Editar producto';
        document.getElementById('form-producto').scrollIntoView({ behavior: 'smooth' });
    }
});

// =====================================
// FORMULARIO (crear y editar)
// =====================================

document.getElementById('form-producto').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    const id = document.getElementById('id_producto').value;

    const datos = {
        id_categoria: Number(document.getElementById('id_categoria').value),
        nombre:       document.getElementById('nombre').value,
        presentacion: document.getElementById('presentacion').value,
        precio:       Number(document.getElementById('precio').value),
        stock:        Number(document.getElementById('stock').value),
        descripcion:  document.getElementById('descripcion').value
    };

    const url    = id ? `api/productos/${id}` : 'api/productos';
    const metodo = id ? 'PUT' : 'POST';

    const respuesta = await fetch(url, {
        method: metodo,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    });
    const resultado = await respuesta.json();

    alert(resultado.message);

    if (resultado.success) {
        limpiarFormulario();
        cargarProductos();
    }
});

function limpiarFormulario() {
    document.getElementById('form-producto').reset();
    document.getElementById('id_producto').value = '';
    document.getElementById('titulo-form').textContent = 'Nuevo producto';
}

document.getElementById('btn-cancelar').addEventListener('click', limpiarFormulario);

cargarProductos();
cargarCategorias();
