let productos = [];

async function cargarProductos() {
    const respuesta = await fetch('api/productos');
    const resultado = await respuesta.json();

    productos = resultado.data;

    mostrarTablaAdmin(productos);
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

cargarProductos();