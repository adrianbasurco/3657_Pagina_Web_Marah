let productos = [];
async function cargarProductos() {
    const respuesta = await fetch('api/productos');
    const resultado = await respuesta.json();

    productos = resultado.data;

    mostrarCaralogo(productos);
    mostrarTabla(productos);
}

function mostrarCaralogo(lista) {

    const contenedor = document.getElementById('lista-productos');
    contenedor.innerHTML = '';

    lista.forEach(producto => {

        const hayStock = producto.stock > 0;

        contenedor.innerHTML += `
            <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                <img src="img/kefir-1litro.jpeg" alt="${producto.nombre}" class="h-36 w-full bg-brand-50 object-contain p-2">
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase text-brand-600">${producto.categoria}</p>
                    <h3 class="mt-1 font-semibold text-slate-900">${producto.nombre} ${producto.presentacion}</h3>
                    <p class="mt-2 text-lg font-bold text-slate-900">S/. ${producto.precio}</p>
                    ${hayStock
                ? '<span class="mt-3 inline-block rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Con stock</span>'
                : '<span class="mt-3 inline-block rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700">Sin stock</span>'}
                </div>
            </article>
        `;
    });
}

function mostrarTabla(lista) {

    const tabla = document.getElementById('tabla');
    tabla.innerHTML = '';

    if (lista.length === 0) {
        tabla.innerHTML = `
        <tr>
            <td colspan="4" class="p-3 text-center text-slate-500">No se encontraron productos</td>
        </tr>
    `;
        return
    }

    lista.forEach(producto => {

        tabla.innerHTML += `
            <tr class="transition hover:bg-brand-50">
                  <td class="p-3 font-medium text-slate-900">${producto.nombre} ${producto.presentacion}</td>
                  <td class="p-3">${producto.categoria}</td>
                  <td class="p-3">S/. ${producto.precio}</td>
                  <td class="p-3">${producto.stock}</td>
            </tr>
        `;
    });
}

cargarProductos();

document.getElementById('formBusqueda').addEventListener('submit', (evento) => {
    evento.preventDefault();

    const texto = document.getElementById('nombre').value.toLowerCase();
    const categoria = document.getElementById('categoria').value;
    const soloStock = document.querySelector('input[name="solo_stock"]').checked;
    const rango = document.querySelector('input[name="rango"]:checked')?.value;

    const filtrados = productos.filter(producto => {
        const precio = Number(producto.precio);

        if (texto && !producto.nombre.toLowerCase().includes(texto)) return false;
        if (categoria && producto.categoria !== categoria) return false;
        if (soloStock && producto.stock <= 0) return false;
        if (rango === 'bajo' && precio > 15) return false;
        if (rango === 'medio' && (precio < 15 || precio > 30)) return false;
        if (rango === 'alto' && precio <= 30) return false;

        return true;
    });

    mostrarTabla(filtrados);
});